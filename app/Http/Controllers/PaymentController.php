<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Cart;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        if (!in_array($payment->method, ['transfer', 'manual'])) {
            return redirect()->back()->with('error', 'Upload bukti hanya untuk pembayaran Transfer Bank atau Pembayaran Manual.');
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
     * Handle Midtrans notification callback
     */
    public function midtransNotification(Request $request)
    {
        try {
            $midtransService = new MidtransService();
            $success = $midtransService->handleNotification($request->getContent());
            
            if ($success) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'failed'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans notification handler exception', [
                'error' => $e->getMessage(),
                'request_body' => $request->getContent()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Simulasi callback Midtrans (untuk testing)
     */
    public function simulateMidtransPayment(Payment $payment)
    {
        // Only for testing - simulate successful Midtrans callback
        if ($payment->method !== 'midtrans' || $payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Invalid payment for Midtrans simulation.');
        }

        // Authorization check
        if ($payment->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            // Simulate successful payment callback
            $transactionId = 'MIDTRANS_SIMULATION_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $payment->markAsPaid($transactionId);

            // Clear cart items for this order
            $order = $payment->order;
            $this->clearCartForOrder($order);

            DB::commit();

            Log::info('Midtrans callback simulated successfully', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'transaction_id' => $transactionId
            ]);

            return redirect()->back()->with('success', 'Pembayaran Midtrans berhasil disimulasi! Status telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Midtrans simulation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan dalam simulasi pembayaran Midtrans.');
        }
    }

    /**
     * Payment success page (from Midtrans redirect)
     */
    public function success(Payment $payment)
    {
        // Redirect to order success page
        return redirect()->route('orders.success', ['order' => $payment->order->id])
            ->with('success', 'Pembayaran berhasil!');
    }

    /**
     * Sync payment status with Midtrans API
     */
    public function syncPaymentStatus(Payment $payment)
    {
        // Authorization check
        if ($payment->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($payment->method !== 'midtrans' || $payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot sync: Payment is not pending Midtrans payment.');
        }

        try {
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production');
            
            $apiUrl = $isProduction 
                ? "https://api.midtrans.com/v2/{$payment->order->order_number}/status"
                : "https://api.sandbox.midtrans.com/v2/{$payment->order->order_number}/status";
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
            ])->get($apiUrl);
            
            if (!$response->successful()) {
                Log::error('Midtrans API sync failed', [
                    'order_number' => $payment->order->order_number,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return redirect()->back()->with('error', 'Failed to sync with Midtrans API.');
            }
            
            $data = $response->json();
            $transactionStatus = $data['transaction_status'] ?? null;
            $fraudStatus = $data['fraud_status'] ?? null;
            
            DB::beginTransaction();
            
            if ($transactionStatus === 'settlement') {
                $payment->markAsPaid($data['transaction_id'] ?? null);
                DB::commit();
                return redirect()->back()->with('success', 'Payment synced successfully! Status updated to PAID.');
            } elseif ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
                $payment->markAsPaid($data['transaction_id'] ?? null);
                DB::commit();
                return redirect()->back()->with('success', 'Payment synced successfully! Status updated to PAID (capture).');
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $payment->update(['status' => Payment::STATUS_FAILED]);
                $payment->order->update(['status' => Order::STATUS_CANCELLED]);
                DB::commit();
                return redirect()->back()->with('warning', "Payment synced: Status is {$transactionStatus}.");
            } else {
                DB::commit();
                return redirect()->back()->with('info', "Payment status in Midtrans: {$transactionStatus}. No update needed.");
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment sync failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error syncing payment status.');
        }
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
