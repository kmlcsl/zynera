<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class MyAuthController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->checkAdminPanelAccess();
            return $next($request);
        });
    }

    /**
     * Check if user has access to admin panel
     */
    protected function checkAdminPanelAccess()
    {
        $user = Auth::user();

        // Only allow admin, kurir, produsen to access admin panel
        $allowedTypes = ['admin', 'kurir', 'produsen'];

        if (!in_array($user->user_type, $allowedTypes)) {
            abort(403, 'Unauthorized. You do not have access to admin panel.');
        }
    }

    /**
     * Check if current user is admin/superadmin
     */
    protected function requireAdmin()
    {
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized. Only Admin/SuperAdmin can access this feature.');
        }
    }

    /**
     * Check if current user is admin or specific user type
     */
    protected function requireAdminOr($userType)
    {
        $currentUserType = Auth::user()->user_type;

        if ($currentUserType !== 'admin' && $currentUserType !== $userType) {
            abort(403, 'Unauthorized. Insufficient permissions.');
        }
    }

    /**
     * Check if user can manage other users
     */
    protected function canManageUsers()
    {
        return Auth::user()->user_type === 'admin';
    }

    /**
     * Check if user can manage products
     */
    protected function canManageProducts()
    {
        $userType = Auth::user()->user_type;
        return in_array($userType, ['admin', 'produsen']);
    }

    /**
     * Check if user can manage orders
     */
    protected function canManageOrders()
    {
        $userType = Auth::user()->user_type;
        return in_array($userType, ['admin', 'produsen', 'kurir']);
    }

    /**
     * Check if user can manage orders
     */
    protected function canManagePayments()
    {
        $userType = Auth::user()->user_type;
        return in_array($userType, ['admin', 'produsen']);
    }

    /**
     * Check if user can manage deliveries
     */
    protected function canManageDeliveries()
    {
        $userType = Auth::user()->user_type;
        return in_array($userType, ['admin', 'kurir']);
    }

    /**
     * Get user permissions for view
     */
    protected function getUserPermissions()
    {
        $userType = Auth::user()->user_type;

        return [
            'can_manage_users' => $userType === 'admin',
            'can_manage_products' => in_array($userType, ['admin', 'produsen']),
            'can_manage_orders' => in_array($userType, ['admin', 'produsen', 'kurir']),
            'can_manage_payments' => in_array($userType, ['admin', 'produsen']),
            'can_manage_deliveries' => in_array($userType, ['admin', 'kurir']),
            'can_manage_categories' => $userType === 'admin',
            'can_view_analytics' => $userType === 'admin',
            'user_type' => $userType
        ];
    }
}
