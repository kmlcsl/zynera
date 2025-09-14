<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        
        $query = Product::with(['category', 'user']);

        if (Auth::user()->user_type === 'produsen') {
            $query->where('user_id', Auth::id());
        }

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();

        // ROLE-BASED STATISTICS
        $statsQuery = Product::query();
        if (Auth::user()->user_type === 'produsen') {
            $statsQuery->where('user_id', Auth::id());
        }

        $stats = [
            'total' => $statsQuery->count(),
            'active' => $statsQuery->where('is_active', true)->count(),
            'low_stock' => $statsQuery->where('stock', '<=', 5)->count(),
            'featured' => $statsQuery->where('is_featured', true)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::where('is_active', true)->get();

        if (Auth::user()->user_type === 'admin') {
            $producers = User::where('user_type', 'produsen')->get();
        } else {
            $producers = collect([Auth::user()]);
        }

        // Get districts for Aceh Barat
        // $districts = Region::kecamatan()
        //     ->whereHas('parent', function ($q) {
        //         $q->where('name', 'Aceh Barat');
        //     })
        //     ->active()
        //     ->orderBy('name')
        //     ->get();

        return view('admin.products.create', compact('categories', 'producers'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'village_id' => 'nullable|exists:regions,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'is_featured' => 'nullable|boolean',
            'ingredients' => 'nullable|array',
            'expired_date' => 'nullable|date|after:today',
        ]);

        // SECURITY CHECK: Produsen hanya bisa buat produk untuk dirinya sendiri
        if (Auth::user()->user_type === 'produsen' && $request->user_id != Auth::id()) {
            return back()->withErrors(['user_id' => 'Anda hanya bisa membuat produk untuk akun Anda sendiri.']);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_featured'] = $request->has('is_featured');

        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        $product->load(['category', 'user', 'reviews.user']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $categories = Category::where('is_active', true)->get();

        if (Auth::user()->user_type === 'admin') {
            $producers = User::where('user_type', 'produsen')->get();
        } else {
            $producers = collect([Auth::user()]);
        }

        // $districts = Region::kecamatan()
        //     ->whereHas('parent', function ($q) {
        //         $q->where('name', 'Aceh Barat');
        //     })
        //     ->active()
        //     ->orderBy('name')
        //     ->get();

        return view('admin.products.edit', compact('product', 'categories', 'producers'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'village_id' => 'nullable|exists:regions,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'is_featured' => 'nullable|boolean',
            'ingredients' => 'nullable|array',
            'expired_date' => 'nullable|date|after:today',
        ]);

        if (Auth::user()->user_type === 'produsen' && $request->user_id != Auth::id()) {
            return back()->withErrors(['user_id' => 'Anda tidak bisa mengubah pemilik produk.']);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_featured'] = $request->has('is_featured');

        // Handle image uploads
        if ($request->hasFile('images')) {
            $rawImages = $product->getRawOriginal('images');
            if ($rawImages) {
                $oldImages = is_string($rawImages) ? json_decode($rawImages, true) : $rawImages;
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    public function getVillagesByDistrict(Request $request)
    {
        $districtId = $request->get('district_id');
        $villages = Region::desa()
            ->where('parent_id', $districtId)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($villages);
    }
}
