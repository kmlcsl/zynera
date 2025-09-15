<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries based on user type
     */
    public function index(Request $request)
    {
        // MANUAL AUTHORIZATION CHECK instead of using policy
        if (!$this->canAccessDeliveries()) {
            abort(403, 'You do not have permission to access delivery management');
        }

        $user = Auth::user();
        $userType = $user->user_type;

        $query = $this->getDeliveriesQuery($userType, $user->id);

        $this->applyFilters($query, $request);

        $deliveries = $query->with([
            'order.user:id,name,email,phone',
            'courier:id,name,email'
        ])->latest()->paginate(15);

        $stats = $this->getDeliveryStats($userType, $user->id);

        $couriers = [];
        if (in_array($userType, ['admin', 'produsen'])) {
            $couriers = User::where('user_type', 'kurir')
                ->where('is_verified', true)
                ->select('id', 'name')->get();
        }

        return view('admin.deliveries.index', compact('deliveries', 'stats', 'couriers'));
    }

    /**
     * Show the form for creating a new delivery
     */
    public function create()
    {
        if (!$this->canCreateDelivery()) {
            abort(403, 'You do not have permission to create deliveries');
        }

        $orders = Order::where('status', Order::STATUS_SHIPPED)
            ->whereDoesntHave('delivery')
            ->with('user:id,name,email')
            ->get();

        $couriers = User::where('user_type', 'kurir')->select('id', 'name')->get();

        return view('admin.deliveries.create', compact('orders', 'couriers'));
    }

    /**
     * Store a newly created delivery
     */
    public function store(Request $request)
    {
        if (!$this->canCreateDelivery()) {
            abort(403, 'You do not have permission to create deliveries');
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'courier_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($request->order_id);
        if ($order->status !== Order::STATUS_SHIPPED) {
            return back()->withInput()
                ->with('error', 'Hanya pesanan dengan status "shipped" yang dapat dibuat pengiriman');
        }

        if ($order->delivery()->exists()) {
            return back()->withInput()
                ->with('error', 'Pengiriman untuk pesanan ini sudah ada');
        }

        if ($request->courier_id) {
            $courier = User::where('id', $request->courier_id)
                ->where('user_type', 'kurir')
                ->first();
            if (!$courier) {
                return back()->withInput()
                    ->with('error', 'Kurir tidak valid');
            }
        }

        $delivery = Delivery::create([
            'order_id' => $request->order_id,
            'courier_id' => $request->courier_id,
            'status' => Delivery::STATUS_ASSIGNED,
            'notes' => $request->notes,
            'assigned_at' => now(),
        ]);

        return redirect()->route('admin.deliveries.show', $delivery)
            ->with('success', 'Pengiriman berhasil dibuat');
    }

    /**
     * Display the specified delivery
     */
    public function show(Delivery $delivery)
    {
        if (!$this->canViewDelivery($delivery)) {
            abort(403, 'You do not have permission to view this delivery');
        }

        $delivery->load([
            'order.user',
            'order.orderItems.product',
            'courier'
        ]);

        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified delivery
     */
    public function edit(Delivery $delivery)
    {
        if (!$this->canUpdateDelivery($delivery)) {
            abort(403, 'You do not have permission to edit this delivery');
        }

        // Only allow editing of active deliveries
        if (in_array($delivery->status, [Delivery::STATUS_DELIVERED, Delivery::STATUS_FAILED])) {
            return redirect()->route('admin.deliveries.show', $delivery)
                ->with('error', 'Pengiriman dengan status ' . $delivery->status_label . ' tidak dapat diedit');
        }

        $couriers = User::where('user_type', 'kurir')->select('id', 'name')->get();

        return view('admin.deliveries.edit', compact('delivery', 'couriers'));
    }

    /**
     * Update the specified delivery
     */
    public function update(Request $request, Delivery $delivery)
    {
        if (!$this->canUpdateDelivery($delivery)) {
            abort(403, 'You do not have permission to update this delivery');
        }

        // Only allow updating of active deliveries
        if (in_array($delivery->status, [Delivery::STATUS_DELIVERED, Delivery::STATUS_FAILED])) {
            return back()->with('error', 'Pengiriman dengan status ' . $delivery->status_label . ' tidak dapat diubah');
        }

        $request->validate([
            'courier_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in([
                Delivery::STATUS_ASSIGNED,
                Delivery::STATUS_PICKED_UP,
                Delivery::STATUS_IN_TRANSIT,
                Delivery::STATUS_DELIVERED,
                Delivery::STATUS_FAILED
            ])],
        ]);

        // Validate courier
        if ($request->courier_id) {
            $courier = User::where('id', $request->courier_id)
                ->where('user_type', 'kurir')
                ->first();
            if (!$courier) {
                return back()->withInput()
                    ->with('error', 'Kurir tidak valid');
            }
        }

        $delivery->update([
            'courier_id' => $request->courier_id,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        // Update timestamps based on status
        $this->updateStatusTimestamps($delivery, $request->status);

        return redirect()->route('admin.deliveries.show', $delivery)
            ->with('success', 'Pengiriman berhasil diperbarui');
    }

    /**
     * Remove the specified delivery
     */
    public function destroy(Delivery $delivery)
    {
        if (!$this->canDeleteDelivery($delivery)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this delivery'
            ]);
        }

        // Only allow deletion of failed deliveries
        if ($delivery->status !== Delivery::STATUS_FAILED) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengiriman yang gagal yang dapat dihapus'
            ]);
        }

        $delivery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengiriman berhasil dihapus'
        ]);
    }

    /**
     * Update delivery status (AJAX)
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        if (!$this->canUpdateDelivery($delivery)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this delivery'
            ]);
        }

        $request->validate([
            'status' => ['required', Rule::in([
                Delivery::STATUS_ASSIGNED,
                Delivery::STATUS_PICKED_UP,
                Delivery::STATUS_IN_TRANSIT,
                Delivery::STATUS_DELIVERED,
                Delivery::STATUS_FAILED
            ])]
        ]);

        $oldStatus = $delivery->status;
        $newStatus = $request->status;

        // Validate status transition
        if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat mengubah status dari {$delivery->status_label} ke " . $this->getStatusLabel($newStatus)
            ]);
        }

        $delivery->update(['status' => $newStatus]);
        $this->updateStatusTimestamps($delivery, $newStatus);

        return response()->json([
            'success' => true,
            'message' => "Status pengiriman berhasil diubah menjadi " . $delivery->status_label
        ]);
    }

    /**
     * Assign courier to delivery (AJAX)
     */
    public function assignCourier(Request $request, Delivery $delivery)
    {
        if (!$this->canUpdateDelivery($delivery)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this delivery'
            ]);
        }

        $request->validate([
            'courier_id' => 'required|exists:users,id'
        ]);

        $courier = User::where('id', $request->courier_id)
            ->where('user_type', 'kurir')
            ->first();

        if (!$courier) {
            return response()->json([
                'success' => false,
                'message' => 'Kurir tidak valid'
            ]);
        }

        // PERBAIKAN: Pastikan status dan timestamp di-update dengan benar
        $delivery->update([
            'courier_id' => $request->courier_id,
            'assigned_by' => Auth::id(), // Track who assigned
            'status' => Delivery::STATUS_ASSIGNED, // Pastikan status assigned
            'assigned_at' => now()
        ]);

        // TAMBAHAN: Log untuk debugging
        Log::info("Delivery {$delivery->id} assigned to courier {$courier->name} (ID: {$courier->id})");

        return response()->json([
            'success' => true,
            'message' => "Pengiriman berhasil ditugaskan ke {$courier->name}",
            'courier' => [
                'id' => $courier->id,
                'name' => $courier->name
            ]
        ]);
    }

    /**
     * Bulk assign courier (AJAX)
     */
    public function bulkAssignCourier(Request $request)
    {
        if (!$this->canCreateDelivery()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to assign couriers'
            ]);
        }

        $request->validate([
            'delivery_ids' => 'required|array',
            'delivery_ids.*' => 'exists:deliveries,id',
            'courier_id' => 'required|exists:users,id'
        ]);

        $courier = User::where('id', $request->courier_id)
            ->where('user_type', 'kurir')
            ->first();

        if (!$courier) {
            return response()->json([
                'success' => false,
                'message' => 'Kurir tidak valid'
            ]);
        }

        $deliveryIds = $request->delivery_ids;
        $deliveries = Delivery::whereIn('id', $deliveryIds)
            ->whereNull('courier_id') // Hanya yang belum di-assign
            ->get();

        $updatedCount = 0;
        foreach ($deliveries as $delivery) {
            $delivery->update([
                'courier_id' => $request->courier_id,
                'assigned_by' => Auth::id(),
                'status' => Delivery::STATUS_ASSIGNED, // Pastikan status assigned
                'assigned_at' => now()
            ]);
            $updatedCount++;

            // Log untuk debugging
            Log::info("Bulk assign: Delivery {$delivery->id} assigned to courier {$courier->name}");
        }

        return response()->json([
            'success' => $updatedCount > 0,
            'message' => "Berhasil assign {$updatedCount} pengiriman ke {$courier->name}"
        ]);
    }

    /**
     * Mark delivery as failed (AJAX)
     */
    public function markAsFailed(Request $request, Delivery $delivery)
    {
        if (!$this->canUpdateDelivery($delivery)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this delivery'
            ]);
        }

        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        $delivery->markAsFailed($request->notes);

        return response()->json([
            'success' => true,
            'message' => 'Pengiriman berhasil ditandai sebagai gagal'
        ]);
    }

    // ========================================
    // MANUAL AUTHORIZATION METHODS
    // ========================================

    /**
     * Check if user can access deliveries
     */
    private function canAccessDeliveries()
    {
        $user = Auth::user();
        return $user && in_array($user->user_type, ['admin', 'kurir', 'produsen']);
    }

    /**
     * Check if user can create delivery
     */
    private function canCreateDelivery()
    {
        $user = Auth::user();
        return $user && in_array($user->user_type, ['admin', 'produsen']);
    }

    /**
     * Check if user can view delivery
     */
    private function canViewDelivery(Delivery $delivery)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        switch ($user->user_type) {
            case 'admin':
                return true;

            case 'kurir':
                // Courier can view their assigned deliveries
                return (int) $delivery->courier_id === (int) $user->id;

            case 'produsen':
                // Producer can view deliveries for orders containing their products
                return $delivery->order->orderItems()
                    ->whereHas('product', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->exists();

            default:
                return false;
        }
    }

    /**
     * Check if user can update delivery
     */
    private function canUpdateDelivery(Delivery $delivery)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        switch ($user->user_type) {
            case 'admin':
                return true;

            case 'kurir':
                // Courier can update their assigned deliveries
                return (int) $delivery->courier_id === (int) $user->id &&
                    !in_array($delivery->status, [Delivery::STATUS_DELIVERED, Delivery::STATUS_FAILED]);

            case 'produsen':
                // Producer can update deliveries for orders containing their products (limited actions)
                return $delivery->order->orderItems()
                    ->whereHas('product', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->exists();

            default:
                return false;
        }
    }

    /**
     * Check if user can delete delivery
     */
    private function canDeleteDelivery(Delivery $delivery)
    {
        $user = Auth::user();
        // Only admin can delete failed deliveries
        return $user && $user->user_type === 'admin' && $delivery->status === Delivery::STATUS_FAILED;
    }

    // ========================================
    // HELPER METHODS (unchanged)
    // ========================================

    /**
     * Get deliveries query based on user type
     */
    private function getDeliveriesQuery($userType, $userId)
    {
        $query = Delivery::query();

        switch ($userType) {
            case 'admin':
                // Admin can see all deliveries
                break;

            case 'kurir':
                // Courier can only see their assigned deliveries
                $query->where('courier_id', $userId);
                break;

            case 'produsen':
                // Producer can see deliveries for orders containing their products
                $query->whereHas('order.orderItems.product', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
                break;

            default:
                // For other user types, return empty query
                $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /**
     * Apply search and filter criteria
     */
    private function applyFilters($query, Request $request)
    {
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', function ($orderQuery) use ($search) {
                    $orderQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhere('shipping_address', 'like', "%{$search}%")
                        ->orWhere('recipient_name', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Courier filter (admin and produsen)
        if ($request->filled('courier_id') && in_array(Auth::user()->user_type, ['admin', 'produsen'])) {
            $query->where('courier_id', $request->courier_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
    }

    /**
     * Get delivery statistics based on user type
     */
    private function getDeliveryStats($userType, $userId)
    {
        $query = $this->getDeliveriesQuery($userType, $userId);

        return [
            'total' => (clone $query)->count(),
            'assigned' => (clone $query)->where('status', Delivery::STATUS_ASSIGNED)->count(),
            'picked_up' => (clone $query)->where('status', Delivery::STATUS_PICKED_UP)->count(),
            'in_transit' => (clone $query)->where('status', Delivery::STATUS_IN_TRANSIT)->count(),
            'delivered' => (clone $query)->where('status', Delivery::STATUS_DELIVERED)->count(),
            'failed' => (clone $query)->where('status', Delivery::STATUS_FAILED)->count(),
        ];
    }

    /**
     * Validate status transition
     */
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            Delivery::STATUS_ASSIGNED => [Delivery::STATUS_PICKED_UP, Delivery::STATUS_FAILED],
            Delivery::STATUS_PICKED_UP => [Delivery::STATUS_IN_TRANSIT, Delivery::STATUS_DELIVERED, Delivery::STATUS_FAILED],
            Delivery::STATUS_IN_TRANSIT => [Delivery::STATUS_DELIVERED, Delivery::STATUS_FAILED],
            Delivery::STATUS_DELIVERED => [], // Final status
            Delivery::STATUS_FAILED => [], // Final status
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }

    /**
     * Update status timestamps
     */
    private function updateStatusTimestamps($delivery, $status)
    {
        switch ($status) {
            case Delivery::STATUS_PICKED_UP:
                if (!$delivery->picked_up_at) {
                    $delivery->update(['picked_up_at' => now()]);
                }
                break;

            case Delivery::STATUS_DELIVERED:
                if (!$delivery->delivered_at) {
                    $delivery->update(['delivered_at' => now()]);
                }
                break;
        }
    }

    /**
     * Get status label
     */
    private function getStatusLabel($status)
    {
        $labels = [
            Delivery::STATUS_ASSIGNED => 'Ditugaskan',
            Delivery::STATUS_PICKED_UP => 'Diambil',
            Delivery::STATUS_IN_TRANSIT => 'Dalam Perjalanan',
            Delivery::STATUS_DELIVERED => 'Terkirim',
            Delivery::STATUS_FAILED => 'Gagal',
        ];

        return $labels[$status] ?? 'Unknown';
    }
}
