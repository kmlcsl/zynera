<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Upload bukti pembayaran (untuk Transfer Bank)
     */
    public function uploadProof(Request $request, Payment $payment)
    {
        // Authorization check
        if ($payment->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate payment method and status
        if ($payment->method !== 'transfer') {
            return redirect()->back()->with('error', 'Upload bukti hanya untuk pembayaran Transfer Bank.');
        }

        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Pembayaran sudah diproses.');
        }

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();

        try {
            // Delete old proof if exists
            if ($payment->proof_image) {
                Storage::disk('public')->delete($payment->proof_image);
            }

            // Store new proof image
            $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');

            // Update payment with proof
            $payment->update([
                'proof_image' => $proofPath,
                'status' => Payment::STATUS_PENDING // Tetap pending, tunggu konfirmasi admin
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran.');
        }
    }

    /**
     * Konfirmasi pembayaran (untuk Admin)
     */
    public function confirmPayment(Request $request, Payment $payment)
    {
        // Only admin can confirm payments
        if (!Auth::user()->user_type === 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            if ($request->action === 'approve') {
                // Approve payment
                $payment->markAsPaid($payment->order->order_number);

                // Clear cart items for this order
                $this->clearCartForOrder($payment->order);

                $message = 'Pembayaran berhasil dikonfirmasi.';
            } else {
                // Reject payment
                $payment->update([
                    'status' => Payment::STATUS_FAILED,
                    'reference_number' => null
                ]);

                // Reset order status to pending
                $payment->order->update(['status' => Order::STATUS_PENDING]);

                $message = 'Pembayaran ditolak.';
            }

            DB::commit();

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses konfirmasi.');
        }
    }

    /**
     * Simulasi callback QRIS (untuk testing)
     */
    public function qrisCallback(Request $request, Payment $payment)
    {
        // Simulate QRIS payment callback
        // In real implementation, this would be called by payment gateway

        $request->validate([
            'status' => 'required|in:success,failed',
            'transaction_id' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            if ($request->status === 'success') {
                // Payment successful
                $payment->markAsPaid($request->transaction_id);

                // Clear cart items for this order
                $this->clearCartForOrder($payment->order);

                $message = 'Pembayaran QRIS berhasil!';
            } else {
                // Payment failed
                $payment->update(['status' => Payment::STATUS_FAILED]);

                $message = 'Pembayaran QRIS gagal.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran.'
            ], 500);
        }
    }

    /**
     * Simulasi pembayaran QRIS (untuk testing)
     */
    public function simulateQrisPayment(Payment $payment)
    {
        // Only for testing - simulate successful QRIS payment
        if ($payment->method !== 'qris' || $payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Invalid payment for simulation.');
        }

        DB::beginTransaction();

        try {
            // Simulate successful payment
            $transactionId = 'QRIS_' . now()->format('YmdHis') . rand(1000, 9999);
            $payment->markAsPaid($transactionId);

            // Clear cart items for this order
            $order = $payment->order; // Get the actual order model
            $this->clearCartForOrder($order);

            DB::commit();

            return redirect()->back()->with('success', 'Pembayaran QRIS berhasil! (Simulasi)');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan dalam simulasi pembayaran.');
        }
    }

    /**
     * Check payment status (untuk polling QRIS)
     */
    public function checkStatus(Payment $payment)
    {
        // Authorization check
        if ($payment->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json([
            'status' => $payment->status,
            'status_label' => $payment->status_label,
            'paid_at' => $payment->paid_at ? $payment->paid_at->toDateTimeString() : null,
            'reference_number' => $payment->reference_number
        ]);
    }

    /**
     * Clear cart items for completed order
     */
    private function clearCartForOrder(Order $order)
    {
        // Get cart items that match this order's products and quantities
        $orderItems = $order->orderItems;

        foreach ($orderItems as $item) {
            $cartItem = Cart::where('user_id', $order->user_id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($cartItem) {
                if ($cartItem->quantity <= $item->quantity) {
                    // Remove entire cart item if quantity matches or less
                    $cartItem->delete();
                } else {
                    // Reduce cart quantity if cart has more than ordered
                    $cartItem->decrement('quantity', $item->quantity);
                }
            }
        }
    }
}
