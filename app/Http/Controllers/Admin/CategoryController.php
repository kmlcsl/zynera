<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = Category::withCount(['products', 'products as active_products_count' => function ($q) {
            $q->where('is_active', true);
        }]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'created_at_desc');
        switch ($sort) {
            case 'created_at_asc':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'products_desc':
                $query->orderBy('products_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $categories = $query->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true)
        ];

        // Handle slug
        if ($request->filled('slug')) {
            $data['slug'] = Str::slug($request->slug);
        } else {
            $data['slug'] = Str::slug($request->name);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->loadCount(['products', 'products as active_products_count' => function ($q) {
            $q->where('is_active', true);
        }]);

        $category->load(['products' => function ($query) {
            $query->with('user:id,name,email')->latest()->take(10);
        }]);

        $stats = [
            'total_products' => $category->products_count,
            'active_products' => $category->active_products_count,
            'inactive_products' => $category->products_count - $category->active_products_count,
            'total_revenue' => 0, // You can calculate this based on your order system
        ];

        return view('admin.categories.show', compact('category', 'stats'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'remove_image' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true)
        ];

        // Handle slug
        if ($request->filled('slug')) {
            $data['slug'] = Str::slug($request->slug);
        } elseif ($request->name !== $category->name) {
            $data['slug'] = Str::slug($request->name);
        }

        // Handle image removal
        if ($request->boolean('remove_image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
                $data['image'] = null;
            }
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus kategori yang memiliki produk.'
            ]);
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.'
        ]);
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Request $request, Category $category)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $category->update([
            'is_active' => $request->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kategori berhasil diubah.'
        ]);
    }

    /**
     * Bulk action for categories
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        $categoryIds = $request->category_ids;
        $action = $request->action;

        try {
            switch ($action) {
                case 'activate':
                    Category::whereIn('id', $categoryIds)->update(['is_active' => true]);
                    $message = 'Kategori berhasil diaktifkan.';
                    break;

                case 'deactivate':
                    Category::whereIn('id', $categoryIds)->update(['is_active' => false]);
                    $message = 'Kategori berhasil dinonaktifkan.';
                    break;

                case 'delete':
                    // Check if any category has products
                    $categoriesWithProducts = Category::whereIn('id', $categoryIds)
                        ->has('products')
                        ->count();

                    if ($categoriesWithProducts > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Beberapa kategori memiliki produk dan tidak dapat dihapus.'
                        ]);
                    }

                    // Delete images and categories
                    $categories = Category::whereIn('id', $categoryIds)->get();
                    foreach ($categories as $category) {
                        if ($category->image) {
                            Storage::disk('public')->delete($category->image);
                        }
                    }

                    Category::whereIn('id', $categoryIds)->delete();
                    $message = 'Kategori berhasil dihapus.';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
