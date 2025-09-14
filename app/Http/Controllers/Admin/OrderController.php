<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of orders based on user type
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = $user->user_type;

        Order::whereHas('payment', function ($q) {
            $q->where('status', 'paid');
        })->where('status', 'pending')->update(['status' => Order::STATUS_PAID]);

        // Base query based on user type
        $query = $this->getOrdersQuery($userType, $user->id);

        // Apply filters
        $this->applyFilters($query, $request);

        // Get paginated orders with relationships - UPDATED TO INCLUDE DELIVERY
        $orders = $query->with([
            'user:id,name,email',
            'orderItems.product.user:id,name',
            'delivery.courier:id,name,email,phone',
            'payment'
        ])->latest()->paginate(15);

        // Get statistics
        $stats = $this->getOrderStats($userType, $user->id);

        // TAMBAH INI - Get available couriers for assign modal
        $availableCouriers = collect();
        if (in_array($userType, ['admin', 'produsen'])) {
            $availableCouriers = User::where('user_type', 'kurir')
                ->where('is_verified', true)
                ->select('id', 'name', 'email', 'phone')
                ->get();
        }

        // Handle export request
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportToExcel($query);
        }

        // PASS availableCouriers ke view
        return view('admin.orders.index', compact('orders', 'stats', 'availableCouriers'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $this->authorize('create', Order::class);

        $customers = User::where('user_type', 'konsumen')->select('id', 'name', 'email')->get();
        $products = Product::where('is_active', true)->with('user:id,name')->get();

        return view('admin.orders.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $price = $product->price;
                $total = $price * $quantity;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ];

                $totalAmount += $total;
            }

            // Add shipping cost
            $shippingCost = $request->shipping_cost ?? 0;
            $totalAmount += $shippingCost;

            // Create order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $request->user_id,
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'status' => Order::STATUS_PENDING,
                'shipping_address' => $request->shipping_address,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load([
            'user',
            'orderItems.product.user',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        // Only allow editing of pending, paid, or processing orders
        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PAID, Order::STATUS_PROCESSING])) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Pesanan dengan status ' . $order->status_label . ' tidak dapat diedit');
        }

        $order->load('orderItems.product');
        $customers = User::where('user_type', 'konsumen')->select('id', 'name', 'email')->get();
        $products = Product::where('is_active', true)->with('user:id,name')->get();

        return view('admin.orders.edit', compact('order', 'customers', 'products'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        // Only allow updating of certain statuses
        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PAID, Order::STATUS_PROCESSING])) {
            return back()->with('error', 'Pesanan dengan status ' . $order->status_label . ' tidak dapat diubah');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED
            ])],
        ]);

        $order->update([
            'shipping_address' => $request->shipping_address,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'shipping_cost' => $request->shipping_cost ?? 0,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        // Recalculate total if shipping cost changed
        $order->total_amount = $order->subtotal + $order->shipping_cost;
        $order->save();

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan berhasil diperbarui');
    }

    /**
     * Remove the specified order
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        // Only allow deletion of cancelled orders
        if ($order->status !== Order::STATUS_CANCELLED) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pesanan yang dibatalkan yang dapat dihapus'
            ]);
        }

        DB::beginTransaction();
        try {
            // Delete order items first
            $order->orderItems()->delete();

            // Delete order
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pesanan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update order status (AJAX)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => ['required', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED
            ])]
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Validate status transition
        if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat mengubah status dari {$oldStatus} ke {$newStatus}"
            ]);
        }

        $order->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "Status pesanan berhasil diubah menjadi {$newStatus}"
        ]);
    }

    /**
     * Bulk update order status (AJAX)
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => ['required', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED
            ])]
        ]);

        $user = Auth::user();
        $orderIds = $request->order_ids;
        $newStatus = $request->status;

        // Get orders based on user type and authorization
        $query = $this->getOrdersQuery($user->user_type, $user->id);
        $orders = $query->whereIn('id', $orderIds)->get();

        $updatedCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            if ($this->isValidStatusTransition($order->status, $newStatus)) {
                $order->update(['status' => $newStatus]);
                $updatedCount++;
            } else {
                $errors[] = "Order #{$order->order_number}: Invalid status transition";
            }
        }

        return response()->json([
            'success' => $updatedCount > 0,
            'message' => "Berhasil mengubah status {$updatedCount} pesanan",
            'errors' => $errors
        ]);
    }

    /**
     * Get orders query based on user type
     */
    private function getOrdersQuery($userType, $userId)
    {
        $query = Order::query();

        switch ($userType) {
            case 'admin':
                // Admin can see all orders
                break;

            case 'produsen':
                // Producer can only see orders containing their products
                $query->whereHas('orderItems.product', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
                break;

            case 'kurir':
                // Courier can see orders that are shipped or ready for delivery
                $query->whereIn('status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED]);
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
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
     * Get order statistics based on user type
     */
    private function getOrderStats($userType, $userId)
    {
        $query = $this->getOrdersQuery($userType, $userId);

        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', Order::STATUS_PENDING)->count(),
            'paid' => (clone $query)->where('status', Order::STATUS_PAID)->count(),
            'processing' => (clone $query)->where('status', Order::STATUS_PROCESSING)->count(),
            'shipped' => (clone $query)->where('status', Order::STATUS_SHIPPED)->count(),
            'delivered' => (clone $query)->where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled' => (clone $query)->where('status', Order::STATUS_CANCELLED)->count(),
        ];
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'GF';
        $date = now()->format('Ymd');

        // Get last order number for today
        $lastOrder = Order::where('order_number', 'like', "{$prefix}{$date}%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Validate status transition
     */
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            Order::STATUS_PENDING => [Order::STATUS_PAID, Order::STATUS_CANCELLED],
            Order::STATUS_PAID => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PROCESSING => [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED],
            Order::STATUS_SHIPPED => [Order::STATUS_DELIVERED],
            Order::STATUS_DELIVERED => [], // Final status
            Order::STATUS_CANCELLED => [], // Final status
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }

    /**
     * Export orders to Excel
     */
    private function exportToExcel($query)
    {
        // This would typically use a package like Laravel Excel
        // For now, return CSV format

        $orders = $query->with(['user', 'orderItems.product'])->get();

        $filename = 'orders_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Total Amount',
                'Shipping Cost',
                'Status',
                'Items Count',
                'Created Date'
            ]);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'Unknown',
                    $order->user->email ?? 'No email',
                    $order->total_amount,
                    $order->shipping_cost,
                    $order->status,
                    $order->orderItems->count(),
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Assign courier to order
     */
    public function assignCourier(Request $request, Order $order)
    {
        if (!in_array($order->status, ['paid', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order dengan status ' . $order->status . ' tidak dapat di-assign kurir. Status harus paid atau processing.'
            ]);
        }

        $request->validate([
            'courier_id' => 'required|exists:users,id',
            'delivery_notes' => 'nullable|string|max:1000'
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

        DB::beginTransaction();
        try {
            // Create or update delivery record
            $delivery = Delivery::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'courier_id' => $request->courier_id,
                    'assigned_by' => Auth::id(),
                    'status' => Delivery::STATUS_ASSIGNED,
                    'assigned_at' => now(),
                    'notes' => $request->delivery_notes ?? 'Pengiriman ditugaskan ke kurir'
                ]
            );

            // Update order status to processing if paid
            if (in_array($order->status, ['paid', 'processing'])) {
                if ($order->status === 'paid') {
                    $order->update(['status' => Order::STATUS_PROCESSING]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menugaskan {$courier->name} untuk pengiriman"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove courier assignment
     */
    public function removeCourierAssignment(Order $order)
    {
        DB::beginTransaction();
        try {
            if ($order->delivery) {
                $order->delivery->update([
                    'courier_id' => null,
                    'assigned_by' => null,
                    'status' => Delivery::STATUS_ASSIGNED,
                    'assigned_at' => null,
                    'notes' => 'Assignment kurir dibatalkan'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Assignment kurir berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk assign courier to multiple orders
     */
    public function bulkAssignCourier(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'courier_id' => 'required|exists:users,id',
            'delivery_notes' => 'nullable|string|max:1000'
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

        DB::beginTransaction();
        try {
            $assignedCount = 0;

            foreach ($request->order_ids as $orderId) {
                $order = Order::find($orderId);
                if ($order && in_array($order->status, ['paid', 'processing'])) {

                    // Create or update delivery
                    Delivery::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'courier_id' => $request->courier_id,
                            'assigned_by' => Auth::id(),
                            'status' => Delivery::STATUS_ASSIGNED,
                            'assigned_at' => now(),
                            'notes' => $request->delivery_notes ?? 'Bulk assignment ke kurir'
                        ]
                    );

                    // Update order status
                    if ($order->status === 'paid') {
                        $order->update(['status' => Order::STATUS_PROCESSING]);
                    }

                    $assignedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil assign {$assignedCount} pesanan ke {$courier->name}"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get available couriers
     */
    public function getAvailableCouriers()
    {
        $couriers = User::where('user_type', 'kurir')
            ->select('id', 'name', 'email', 'phone')
            ->get();

        return response()->json([
            'success' => true,
            'couriers' => $couriers
        ]);
    }

    private function updateOrderStatusBasedOnPayment(Order $order)
    {
        if ($order->payment && $order->payment->status === 'paid' && $order->status === 'pending') {
            $order->update(['status' => Order::STATUS_PAID]);
        }
    }

    /**
     * Generate WhatsApp follow-up message and redirect
     */
    public function whatsappFollowup(Order $order)
    {
        $this->authorize('update', $order);

        if (!$order->delivery || !$order->delivery->courier) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan belum memiliki kurir yang ditugaskan'
            ]);
        }

        $courier = $order->delivery->courier;

        if (!$courier->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Kurir tidak memiliki nomor HP'
            ]);
        }

        // Format nomor HP Indonesia
        $phone = $this->formatPhoneNumber($courier->phone);

        // Template pesan dengan emoji yang aman
        $message = "*PEMBERITAHUAN PENGIRIMAN*\n\n";
        $message .= "Halo,\n";
        $message .= "Paket dengan No. Pesanan: *{$order->order_number}* telah ditujukan kepada Anda.\n\n";
        $message .= "Mohon segera lakukan proses pengiriman dan silakan login ke sistem untuk melakukan konfirmasi.\n\n";
        $message .= "Terima kasih atas kerja samanya";

        // Generate WhatsApp URL
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'message' => 'URL WhatsApp berhasil dibuat'
        ]);
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Hapus semua karakter non-digit
        $cleanPhone = preg_replace('/\D/', '', $phone);

        // Format nomor Indonesia
        if (substr($cleanPhone, 0, 3) === '620') {
            $cleanPhone = substr($cleanPhone, 2); // Hapus 62, biarkan 0
        } elseif (substr($cleanPhone, 0, 2) === '62') {
            $cleanPhone = '0' . substr($cleanPhone, 2); // Tambah 0 di depan
            $cleanPhone = preg_replace('/\D/', '', $cleanPhone); // Clean lagi
        } elseif (!substr($cleanPhone, 0, 2) === '08') {
            $cleanPhone = '62' . ltrim($cleanPhone, '0');
        }

        // Untuk WhatsApp, gunakan format internasional tanpa +
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        return $cleanPhone;
    }
}
