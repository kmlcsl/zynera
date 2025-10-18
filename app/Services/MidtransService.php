<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    private $serverKey;
    private $clientKey;
    private $merchantId;
    private $isProduction;
    private $apiUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->merchantId = config('midtrans.merchant_id');
        $this->isProduction = config('midtrans.is_production');
        $this->apiUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2/'
            : 'https://api.sandbox.midtrans.com/v2/';
    }

    /**
     * Create Snap Token for payment
     */
    public function createSnapToken(Order $order)
    {
        try {
            $snapApiUrl = $this->isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $order->recipient_name,
                    'email' => $order->user->email ?? '',
                    'phone' => $order->recipient_phone,
                ],

                'item_details' => $this->buildItemDetails($order),
                'callbacks' => [
                    'finish' => route('orders.success', ['order' => $order->id]),
                    'error' => route('orders.index'),
                    'pending' => route('orders.index'),
                ],
                'enabled_payments' => [
                    'credit_card', 'bank_transfer', 'bca_va', 'bni_va',
                    'bri_va', 'other_va', 'gopay', 'qris', 'shopeepay',
                ],
                'credit_card' => [
                    'secure' => true,
                ],
            ];

            // Add shipping address only for courier orders
            if ($order->shipping_method === 'courier' && $order->shipping_address) {
                $params['customer_details']['billing_address'] = [
                    'address' => $order->shipping_address,
                ];
                $params['customer_details']['shipping_address'] = [
                    'address' => $order->shipping_address,
                ];
            }

            Log::info('Creating Midtrans Snap token', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $order->total_amount,
                'url' => $snapApiUrl,
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':'),
                ])
                ->post($snapApiUrl, $params);

            Log::info('Midtrans API Response', [
                'order_id' => $order->id,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['redirect_url'] ?? null;
            }

            Log::error('Midtrans Snap Token creation failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Build item details for Midtrans
     */
    private function buildItemDetails(Order $order)
    {
        $items = [];

        // Add order items
        foreach ($order->orderItems as $item) {
            $items[] = [
                'id' => $item->product->id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
                'category' => $item->product->category->name ?? 'Product',
            ];
        }

        // Add service fee as item
        if ($order->service_fee > 0) {
            $items[] = [
                'id' => 'service_fee',
                'price' => (int) $order->service_fee,
                'quantity' => 1,
                'name' => 'Biaya Layanan',
                'category' => 'Fee',
            ];
        }

        // Add shipping cost as item
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'shipping_cost',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
                'category' => 'Shipping',
            ];
        }

        return $items;
    }

    /**
     * Handle Midtrans notification/callback
     */
    public function handleNotification($notificationBody)
    {
        try {
            $notification = json_decode($notificationBody, true);
            $orderId = $notification['order_id'] ?? null;
            $transactionStatus = $notification['transaction_status'] ?? null;
            $fraudStatus = $notification['fraud_status'] ?? null;

            if (!$orderId) {
                Log::warning('Midtrans notification missing order_id', ['notification' => $notification]);
                return false;
            }

            // Find order
            $order = Order::where('order_number', $orderId)->first();
            if (!$order || !$order->payment) {
                Log::warning('Order or payment not found for Midtrans notification', [
                    'order_id' => $orderId,
                    'order_exists' => !!$order,
                    'payment_exists' => $order ? !!$order->payment : false,
                ]);
                return false;
            }

            // Verify signature
            if (!$this->verifySignature($notification)) {
                Log::error('Invalid Midtrans signature', ['order_id' => $orderId]);
                return false;
            }

            // Process payment status
            $payment = $order->payment;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // Payment is pending due to fraud detection
                    Log::info('Midtrans payment challenge', ['order_id' => $orderId]);
                } elseif ($fraudStatus == 'accept') {
                    // Payment successful
                    $payment->markAsPaid($notification['transaction_id'] ?? null);
                    Log::info('Midtrans payment captured and accepted', ['order_id' => $orderId]);
                }
            } elseif ($transactionStatus == 'settlement') {
                // Payment successful
                $payment->markAsPaid($notification['transaction_id'] ?? null);
                Log::info('Midtrans payment settled', ['order_id' => $orderId]);
            } elseif ($transactionStatus == 'pending') {
                // Payment pending
                Log::info('Midtrans payment pending', ['order_id' => $orderId]);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                // Payment failed
                $payment->update(['status' => Payment::STATUS_FAILED]);
                Log::info('Midtrans payment failed', ['order_id' => $orderId, 'status' => $transactionStatus]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Midtrans notification handling failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'notification' => $notificationBody,
            ]);

            return false;
        }
    }

    /**
     * Verify Midtrans signature
     */
    private function verifySignature($notification)
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    /**
     * Get client key for frontend
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * Get Snap URL for frontend
     */
    public function getSnapUrl()
    {
        return config('midtrans.snap_url');
    }
}
