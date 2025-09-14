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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    use AuthorizesRequests;

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

        try {
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

            return redirect()->route('orders.success', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silahkan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();

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
     * Display specific order
     */
    public function show(Order $order)
    {
        // Authorization check
        $this->authorize('view', $order);

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
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        // Authorization check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
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
     * Display order success page
     */
    public function success(Order $order)
    {
        // Authorization check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load necessary relationships
        $order->load([
            'orderItems.product',
            'payment',
            'user'
        ]);

        return view('orders.success', compact('order'));
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
     * Clear cart items for completed order
     */
    private function clearCartForOrder(Order $order)
    {
        // Clear all cart items for this user when order is completed
        Cart::where('user_id', $order->user_id)->delete();
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
}
