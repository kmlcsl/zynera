<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;

class CheckOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:check-status {--order-id= : Specific order ID to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check order and payment status for debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->option('order-id');
        
        if ($orderId) {
            $order = Order::with(['payment', 'user'])->find($orderId);
            if (!$order) {
                $this->error("Order with ID {$orderId} not found.");
                return 1;
            }
            $orders = collect([$order]);
        } else {
            $orders = Order::with(['payment', 'user'])->latest()->take(5)->get();
        }

        $this->info('=== ORDER STATUS CHECK ===');
        
        foreach ($orders as $order) {
            $this->line('');
            $this->info("Order ID: {$order->id}");
            $this->line("Order Number: {$order->order_number}");
            $this->line("User: {$order->user->name} ({$order->user->email})");
            $this->line("Order Status: {$order->status}");
            $this->line("Shipping Method: {$order->shipping_method}");
            $this->line("Total Amount: Rp " . number_format($order->total_amount, 0, ',', '.'));
            $this->line("Created At: {$order->created_at}");
            
            if ($order->payment) {
                $this->line('--- Payment Info ---');
                $this->line("Payment Method: {$order->payment->method}");
                $this->line("Payment Status: {$order->payment->status}");
                $this->line("Reference: {$order->payment->reference_number}");
                $this->line("Paid At: {$order->payment->paid_at}");
            } else {
                $this->error('No payment record found!');
            }
            
            $this->line('========================');
        }
        
        return 0;
    }
}
