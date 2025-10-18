<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:sync {order-number? : Specific order number to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually sync payment status with Midtrans for development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderNumber = $this->argument('order-number');
        
        if ($orderNumber) {
            $orders = Order::where('order_number', $orderNumber)->with('payment')->get();
            if ($orders->isEmpty()) {
                $this->error("Order {$orderNumber} not found.");
                return 1;
            }
        } else {
            // Sync all pending Midtrans payments from last 24 hours
            $orders = Order::whereHas('payment', function($q) {
                $q->where('method', 'midtrans')
                  ->where('status', 'pending');
            })
            ->where('created_at', '>=', now()->subDay())
            ->with('payment')
            ->get();
        }
        
        if ($orders->isEmpty()) {
            $this->info('No pending Midtrans payments found to sync.');
            return 0;
        }
        
        $this->info("Found {$orders->count()} order(s) to sync:");
        
        foreach ($orders as $order) {
            $this->syncOrder($order);
        }
        
        return 0;
    }
    
    private function syncOrder(Order $order)
    {
        $this->line("");
        $this->info("Syncing Order: {$order->order_number}");
        
        if (!$order->payment || $order->payment->method !== 'midtrans') {
            $this->warn('Skipping: Not a Midtrans payment');
            return;
        }
        
        try {
            // Call Midtrans API to check status
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production');
            
            $apiUrl = $isProduction 
                ? "https://api.midtrans.com/v2/{$order->order_number}/status"
                : "https://api.sandbox.midtrans.com/v2/{$order->order_number}/status";
            
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
            ])->get($apiUrl);
            
            if (!$response->successful()) {
                $this->error("API Error: {$response->status()} - {$response->body()}");
                return;
            }
            
            $data = $response->json();
            $transactionStatus = $data['transaction_status'] ?? null;
            $fraudStatus = $data['fraud_status'] ?? null;
            
            $this->line("Midtrans Status: {$transactionStatus}");
            
            $payment = $order->payment;
            $updated = false;
            
            // Process status based on Midtrans response
            if ($transactionStatus === 'settlement') {
                $payment->markAsPaid($data['transaction_id'] ?? null);
                $this->info('✅ Payment marked as PAID (settlement)');
                $updated = true;
            } elseif ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $payment->markAsPaid($data['transaction_id'] ?? null);
                    $this->info('✅ Payment marked as PAID (capture accepted)');
                    $updated = true;
                } elseif ($fraudStatus === 'challenge') {
                    $this->warn('⚠️  Payment under review (fraud challenge)');
                }
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $payment->update(['status' => Payment::STATUS_FAILED]);
                $order->update(['status' => Order::STATUS_CANCELLED]);
                $this->warn("❌ Payment marked as FAILED ({$transactionStatus})");
                $updated = true;
            } elseif ($transactionStatus === 'pending') {
                $this->info('⏳ Payment still pending in Midtrans');
            }
            
            if ($updated) {
                $this->info("Order {$order->order_number} synced successfully!");
            }
            
        } catch (\Exception $e) {
            $this->error("Sync failed: {$e->getMessage()}");
            Log::error('Payment sync failed', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage()
            ]);
        }
    }
}
