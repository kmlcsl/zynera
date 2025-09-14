<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MyAuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends MyAuthController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // parent::__construct();

        // // Additional check: Only admin can access user management
        // $this->middleware(function ($request, $next) {
        //     $this->requireAdmin();
        //     return $next($request);
        // });
    }

    /**
     * Display a listing of the resource.
     * Show only admin panel users (admin, kurir, produsen)
     * Skip konsumen as they have their own interface
     */
    public function index(Request $request)
    {
        // Only show admin panel users, exclude konsumen
        $query = User::whereIn('user_type', ['admin', 'kurir', 'produsen']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by user type
        if ($request->user_type && in_array($request->user_type, ['admin', 'kurir', 'produsen'])) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by verification status
        if ($request->verification === 'verified') {
            $query->where('is_verified', true);
        } elseif ($request->verification === 'unverified') {
            $query->where('is_verified', false);
        }

        $users = $query->latest()->paginate(10);

        // Statistics - Only for admin panel users
        $stats = [
            'admin' => User::where('user_type', 'admin')->count(),
            'produsen' => User::where('user_type', 'produsen')->count(),
            'kurir' => User::where('user_type', 'kurir')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'user_type' => 'required|in:admin,produsen,kurir',
            'is_verified' => 'boolean',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['is_verified'] = $request->has('is_verified');
        $data['email_verified_at'] = $request->has('is_verified') ? now() : null;

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User admin panel berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Ensure user is admin panel user
        if (!in_array($user->user_type, ['admin', 'kurir', 'produsen'])) {
            abort(404, 'User not found in admin panel.');
        }

        $user->load(['orders', 'products', 'reviews']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ensure user is admin panel user
        if (!in_array($user->user_type, ['admin', 'kurir', 'produsen'])) {
            abort(404, 'User not found in admin panel.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ensure user is admin panel user
        if (!in_array($user->user_type, ['admin', 'kurir', 'produsen'])) {
            abort(404, 'User not found in admin panel.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'user_type' => 'required|in:admin,produsen,kurir',
            'is_verified' => 'boolean',
        ]);

        $data = $request->except(['password', 'password_confirmation']);

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['is_verified'] = $request->has('is_verified');

        // Set email_verified_at if verified
        if ($request->has('is_verified') && !$user->email_verified_at) {
            $data['email_verified_at'] = now();
        } elseif (!$request->has('is_verified')) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User admin panel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Ensure user is admin panel user
        if (!in_array($user->user_type, ['admin', 'kurir', 'produsen'])) {
            abort(404, 'User not found in admin panel.');
        }

        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        // Prevent deleting the last admin
        if ($user->user_type === 'admin' && User::where('user_type', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User admin panel berhasil dihapus');
    }

    /**
     * Toggle user verification status
     */
    public function toggleVerification(User $user)
    {
        // Ensure user is admin panel user
        if (!in_array($user->user_type, ['admin', 'kurir', 'produsen'])) {
            abort(404, 'User not found in admin panel.');
        }

        $user->update([
            'is_verified' => !$user->is_verified,
            'email_verified_at' => !$user->is_verified ? now() : null
        ]);

        $status = $user->is_verified ? 'diverifikasi' : 'dibatalkan verifikasinya';

        return redirect()->back()
            ->with('success', "User {$user->name} berhasil {$status}");
    }

    /**
     * Bulk actions for admin panel users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,unverify,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        // Only admin panel users
        $users = User::whereIn('id', $request->user_ids)
            ->whereIn('user_type', ['admin', 'kurir', 'produsen']);

        $count = $users->count();

        switch ($request->action) {
            case 'verify':
                $users->update([
                    'is_verified' => true,
                    'email_verified_at' => now()
                ]);
                $message = "{$count} user admin panel berhasil diverifikasi";
                break;

            case 'unverify':
                $users->update([
                    'is_verified' => false,
                    'email_verified_at' => null
                ]);
                $message = "{$count} user admin panel berhasil dibatalkan verifikasinya";
                break;

            case 'delete':
                // Prevent deleting current admin and last admin
                $users->where('id', '!=', Auth::id())
                    ->where(function ($q) {
                        $q->where('user_type', '!=', 'admin')
                            ->orWhere(function ($q2) {
                                $q2->where('user_type', 'admin')
                                    ->havingRaw('(SELECT COUNT(*) FROM users WHERE user_type = "admin") > 1');
                            });
                    })->delete();
                $message = "{$count} user admin panel berhasil dihapus";
                break;
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * Show permissions management index
     */
    public function permissionsIndex()
    {
        $query = User::whereIn('user_type', ['admin', 'produsen', 'kurir'])
            ->with('permissions');

        // Search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }

        // Filter by user type
        if (request('user_type')) {
            $query->where('user_type', request('user_type'));
        }

        // Filter by permission status
        if (request('permission_status')) {
            if (request('permission_status') === 'custom') {
                $query->has('permissions');
            } elseif (request('permission_status') === 'default') {
                $query->doesntHave('permissions');
            }
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.permissions.index', compact('users'));
    }

    /**
     * Show user permissions page
     */
    public function permissions(User $user)
    {
        $listRoutes = new \App\Classes\ListRoutes();
        $allRoutes = $listRoutes->getAllRouteNames();
        $userRoutes = $listRoutes->getRoutesByUserType($user->user_type);

        // Get user's current permissions
        $userPermissions = $user->permissions()->pluck('is_allowed', 'route_name')->toArray();

        return view('admin.users.permissions.show', compact('user', 'allRoutes', 'userRoutes', 'userPermissions'));
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'boolean',
        ]);

        // Update permissions berdasarkan ListRoutes
        $listRoutes = new \App\Classes\ListRoutes();
        $userRoutes = $listRoutes->getRoutesByUserType($user->user_type);

        // Delete existing permissions
        $user->permissions()->delete();

        // Add new permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $routeName => $isAllowed) {
                $user->permissions()->create([
                    'route_name' => $routeName,
                    'is_allowed' => (bool)$isAllowed,
                ]);
            }
        }

        return redirect()->route('admin.users.permissions.show', $user)
            ->with('success', 'Permission user berhasil diperbarui');
    }
}
