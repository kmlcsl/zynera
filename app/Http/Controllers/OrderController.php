<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Only require authentication - no additional restrictions for konsumen
        $this->middleware('auth');

        // Log for debugging authorization issues
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                Log::info('OrderController accessed by user', [
                    'user_id' => Auth::id(),
                    'user_type' => Auth::user()->user_type,
                    'email' => Auth::user()->email,
                    'route' => $request->route() ? $request->route()->getName() : 'unknown',
                    'method' => $request->method(),
                    'url' => $request->url()
                ]);
            }
            return $next($request);
        });
    }

    /**
     * Display checkout page
     */
    public function checkout(Request $request)
    {
        // Get all cart items for current user
        $allCarts = Cart::with(['product.category', 'product.user'])
            ->where('user_id', Auth::id())
            ->get();

        if ($allCarts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        $carts = $allCarts;

        // Filter items based on source
        if ($request->has('items') && !empty($request->items)) {
            // Coming from cart with selected items
            $selectedItems = explode(',', $request->items);
            $carts = $allCarts->whereIn('id', $selectedItems);
        } elseif (session()->has('buy_now_item')) {
            // Coming from "Buy Now" button
            $buyNowProductId = session('buy_now_item');
            $carts = $allCarts->where('product_id', $buyNowProductId);
            // Clear the session after use
            session()->forget('buy_now_item');
        }

        // Validate all items have sufficient stock
        foreach ($carts as $cart) {
            if (!$cart->product->is_active) {
                return redirect()->route('cart.index')
                    ->with('error', "Produk {$cart->product->name} tidak tersedia");
            }

            if ($cart->product->stock < $cart->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok produk {$cart->product->name} tidak mencukupi");
            }
        }

        // Calculate totals
        $subtotal = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        $shippingCost = 5000;
        $serviceFee = $subtotal * 0.02; // 2% service fee on subtotal only
        $total = $subtotal + $serviceFee + $shippingCost;

        return view('orders.checkout', compact('carts', 'subtotal', 'serviceFee', 'shippingCost', 'total'));
    }

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        try {
            // Log the attempt
            Log::info('Order creation attempted', [
                'user_id' => Auth::id(),
                'user_type' => Auth::user()->user_type,
                'request_data' => $request->only(['recipient_name', 'recipient_phone', 'payment_method'])
            ]);

            $request->validate([
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string|max:1000',
                'payment_method' => 'required|in:cod,transfer,qris',
                'notes' => 'nullable|string|max:500'
            ]);

            // Get cart items
            $carts = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($carts->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
            }

            // Validate stock availability again before creating order
            foreach ($carts as $cart) {
                if (!$cart->product->is_active) {
                    return redirect()->route('cart.index')
                        ->with('error', "Produk {$cart->product->name} tidak tersedia");
                }

                if ($cart->product->stock < $cart->quantity) {
                    return redirect()->route('cart.index')
                        ->with('error', "Stok produk {$cart->product->name} tidak mencukupi");
                }
            }

            DB::beginTransaction();

            // Calculate totals
            $subtotal = $carts->sum(function ($cart) {
                return $cart->product->price * $cart->quantity;
            });

            $shippingCost = 5000;
            $serviceFee = $subtotal * 0.02; // 2% service fee on subtotal only
            $totalAmount = $subtotal + $serviceFee + $shippingCost;
            $totalItems = $carts->sum('quantity');

            // Create order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'shipping_cost' => $shippingCost,
                'total_items' => $totalItems,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'status' => Order::STATUS_PENDING
            ]);

            // Create order items and update product stock
            foreach ($carts as $cart) {
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'total' => $cart->product->price * $cart->quantity
                ]);

                // Update product stock
                $cart->product->decrement('stock', $cart->quantity);
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'method' => $request->payment_method,
                'amount' => $totalAmount,
                'status' => Payment::STATUS_PENDING
            ]);

            if ($request->payment_method === 'cod') {
                // COD: Mark payment as paid and create delivery
                $payment->markAsPaid('COD_' . $order->order_number);
            } else {
                // Non-COD: Create delivery record untuk tracking (menunggu payment)
                Delivery::create([
                    'order_id' => $order->id,
                    'status' => Delivery::STATUS_ASSIGNED,
                    'assigned_at' => now(),
                    'notes' => 'Menunggu konfirmasi pembayaran dan assignment kurir'
                ]);
            }

            DB::commit();

            // Clear cart untuk item yang sudah di-checkout
            Cart::where('user_id', Auth::id())->delete();

            // Log successful order creation before redirect
            Log::info('=== ORDER CREATED SUCCESSFULLY ===', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'order_number' => $order->order_number,
                'redirect_route' => 'orders.success',
                'redirect_url' => route('orders.success', ['order' => $order->id])
            ]);

            // Cek apakah order bisa diload ulang
            $testOrder = Order::find($order->id);
            Log::info('=== ORDER VERIFICATION ===', [
                'can_find_order' => $testOrder ? 'YES' : 'NO',
                'order_user_id' => $testOrder ? $testOrder->user_id : 'N/A',
                'matches_current_user' => $testOrder ? ($testOrder->user_id == Auth::id()) : 'N/A'
            ]);

            return redirect()->route('orders.success', ['order' => $order->id])
                ->with('success', 'Pesanan berhasil dibuat! Silahkan lakukan pembayaran.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Order creation validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Order creation general error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'class' => get_class($e)
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan saat membuat pesanan. Silahkan coba lagi.');
        }
    }

    /**
     * Display orders list for current user
     */
    public function index()
    {
        $orders = Order::with(['orderItems.product.category', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display specific order - REMOVED AUTHORIZATION POLICY
     */
    public function show(Order $order)
    {
        // Manual authorization check without policy
        if (!$this->canViewOrder($order)) {
            Log::warning('Unauthorized access to order details', [
                'order_id' => $order->id,
                'order_user_id' => $order->user_id,
                'current_user_id' => Auth::id(),
                'current_user_type' => Auth::user()->user_type ?? 'unknown'
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat pesanan ini.');
        }

        // Load all necessary relationships
        $order->load([
            'orderItems.product.category',
            'orderItems.product.user',
            'payment',
            'user',
            'delivery.courier'
        ]);

        return view('orders.show', compact('order'));
    }

    /**
     * Display order success page - FIXED WITHOUT POLICY
     */
    public function success(Order $order)
    {
        // Check if user is authenticated first
        if (!Auth::check()) {
            Log::warning('Unauthenticated user trying to access order success page', [
                'order_id' => $order->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat detail pesanan.');
        }

        // DEBUGGING: Log semua detail untuk debugging
        Log::info('=== SUCCESS PAGE ACCESS ATTEMPT ===', [
            'order_id' => $order->id,
            'order_user_id' => $order->user_id,
            'current_user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'direct_match' => $order->user_id === Auth::id(),
            'user_type' => Auth::user()->user_type,
            'session_id' => session()->getId()
        ]);

        // TEMPORARY: Bypass authorization untuk debugging
        // Manual authorization check without using policy
        if (!$this->canViewOrder($order)) {
            Log::warning('Unauthorized access to order success page', [
                'order_id' => $order->id,
                'order_user_id' => $order->user_id,
                'current_user_id' => Auth::id(),
                'current_user_type' => Auth::user()->user_type ?? 'unknown',
                'current_user_email' => Auth::user()->email ?? 'unknown',
                'ip' => request()->ip()
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat pesanan ini.');
        }

        // Log successful access
        Log::info('Order success page accessed successfully', [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'order_number' => $order->order_number,
            'user_type' => Auth::user()->user_type
        ]);

        // Load necessary relationships
        $order->load([
            'orderItems.product',
            'payment',
            'user'
        ]);

        return view('orders.success', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated user trying to cancel order', [
                'order_id' => $order->id,
                'ip' => request()->ip()
            ]);

            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Authorization check
        if (!$this->canViewOrder($order)) {
            Log::warning('Unauthorized order cancellation attempt', [
                'order_id' => $order->id,
                'order_user_id' => $order->user_id,
                'current_user_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak dapat membatalkan pesanan ini.');
        }

        // Check if order can be cancelled
        if (!$order->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan pada status saat ini.');
        }

        DB::beginTransaction();

        try {
            // Restore product stock
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Update order status
            $order->update([
                'status' => Order::STATUS_CANCELLED
            ]);

            // Update payment status if exists
            if ($order->payment) {
                $order->payment->update([
                    'status' => Payment::STATUS_FAILED
                ]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pesanan berhasil dibatalkan dan stok produk telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
    }

    /**
     * Update order status (for admin/seller)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Validate status transition
        if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
            return redirect()->back()
                ->with('error', 'Perubahan status tidak valid.');
        }

        DB::beginTransaction();

        try {
            // Update order status
            $order->update(['status' => $newStatus]);

            // Handle specific status changes
            switch ($newStatus) {
                case Order::STATUS_PAID:
                    if ($order->payment) {
                        $order->payment->markAsPaid();
                    }
                    break;

                case Order::STATUS_DELIVERED:
                    // Clear cart when order is delivered
                    Cart::where('user_id', $order->user_id)->delete();

                    // For COD, mark as paid when delivered
                    if ($order->payment && $order->payment->method === 'cod') {
                        $order->payment->markAsPaid('COD_' . $order->order_number);
                    }
                    break;

                case Order::STATUS_CANCELLED:
                    // Restore stock if cancelled
                    foreach ($order->orderItems as $item) {
                        $item->product->increment('stock', $item->quantity);
                    }
                    break;
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status pesanan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate status.');
        }
    }

    /**
     * Display tracking page for active orders
     */
    public function tracking()
    {
        $activeOrders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['paid', 'processing', 'shipped'])
            ->with(['delivery.courier', 'payment'])
            ->latest()
            ->get();

        return view('orders.tracking', compact('activeOrders'));
    }

    /**
     * Helper method to check if user can view order
     */
    private function canViewOrder(Order $order)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // FIXED: Convert to int untuk comparison yang benar
        $orderUserId = (int) $order->user_id;
        $currentUserId = (int) $user->id;

        // Log untuk debugging
        Log::info('canViewOrder check', [
            'order_user_id' => $order->user_id,
            'order_user_id_type' => gettype($order->user_id),
            'current_user_id' => $user->id,
            'current_user_id_type' => gettype($user->id),
            'order_user_id_int' => $orderUserId,
            'current_user_id_int' => $currentUserId,
            'comparison_result' => $orderUserId === $currentUserId
        ]);

        // User can view their own orders
        if ($orderUserId === $currentUserId) {
            Log::info('ACCESS GRANTED: Order belongs to user');
            return true;
        }

        // Admin can view all orders
        if ($user->user_type === 'admin') {
            Log::info('ACCESS GRANTED: User is admin');
            return true;
        }

        // Produsen can view orders containing their products
        if ($user->user_type === 'produsen') {
            $hasProduct = $order->orderItems()
                ->whereHas('product', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->exists();

            if ($hasProduct) {
                Log::info('ACCESS GRANTED: Produsen has product in order');
                return true;
            }
        }

        Log::warning('ACCESS DENIED: No matching conditions');
        return false;
    }

    /**
     * Debug method untuk testing authorization - FIXED
     */
    public function debugAuth(Order $order)
    {
        $user = Auth::user();

        Log::info('=== DEBUG AUTH CHECK ===', [
            'order_id' => $order->id,
            'order_user_id' => $order->user_id,
            'current_user_id' => $user ? $user->id : null,
            'current_user_type' => $user ? $user->user_type : null,
            'current_user_email' => $user ? $user->email : null,
            'session_id' => session()->getId()
        ]);

        // Test manual authorization check
        $canView = false;
        try {
            $canView = $this->canViewOrder($order);
            Log::info('Manual authorization check result', ['can_view' => $canView]);
        } catch (\Exception $e) {
            Log::error('Manual authorization check failed', ['error' => $e->getMessage()]);
        }

        // Test Gate check (jika ada)
        $gateCanView = false;
        try {
            $gateCanView = Gate::allows('view', $order);
            Log::info('Gate check result', ['gate_can_view' => $gateCanView]);
        } catch (\Exception $e) {
            Log::error('Gate check failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'authenticated' => Auth::check(),
            'user_id' => $user ? $user->id : null,
            'user_type' => $user ? $user->user_type : null,
            'user_email' => $user ? $user->email : null,
            'order_id' => $order->id,
            'order_user_id' => $order->user_id,
            'order_number' => $order->order_number,
            'can_view_manual' => $canView,
            'can_view_gate' => $gateCanView,
            'direct_comparison' => $order->user_id === ($user ? $user->id : null),
            'session_id' => session()->getId(),
            'message' => $canView ? 'ACCESS SHOULD BE GRANTED' : 'ACCESS DENIED BY MANUAL CHECK'
        ]);
    }

    /**
     * Test method tanpa policy check
     */
    public function testSuccess(Order $order)
    {
        // FIXED: Convert to int for proper comparison
        $orderUserId = (int) $order->user_id;
        $currentUserId = (int) Auth::id();

        Log::info('=== TEST SUCCESS ACCESSED ===', [
            'order_id' => $order->id,
            'order_user_id' => $order->user_id,
            'order_user_id_type' => gettype($order->user_id),
            'current_user_id' => Auth::id(),
            'current_user_id_type' => gettype(Auth::id()),
            'order_user_id_int' => $orderUserId,
            'current_user_id_int' => $currentUserId,
            'matches' => $orderUserId === $currentUserId,
            'strict_comparison' => $order->user_id === Auth::id(),
            'loose_comparison' => $order->user_id == Auth::id()
        ]);

        if ($orderUserId !== $currentUserId && (Auth::user()->user_type ?? '') !== 'admin') {
            return response()->json([
                'status' => 'ACCESS_DENIED',
                'reason' => 'Order does not belong to current user',
                'order_user_id' => $order->user_id,
                'order_user_id_type' => gettype($order->user_id),
                'current_user_id' => Auth::id(),
                'current_user_id_type' => gettype(Auth::id()),
                'order_user_id_int' => $orderUserId,
                'current_user_id_int' => $currentUserId,
                'fixed_comparison' => $orderUserId === $currentUserId ? 'MATCH' : 'NO_MATCH'
            ]);
        }

        return response()->json([
            'status' => 'ACCESS_GRANTED',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'message' => 'This order belongs to current user',
            'comparison_fixed' => true
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        do {
            $orderNumber = 'GF' . date('Ymd') . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
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
            Order::STATUS_CANCELLED => [] // Final status
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }
}
