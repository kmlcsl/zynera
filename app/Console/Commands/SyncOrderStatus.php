<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class SyncOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:sync-status';

    /**
     * The description of the console command.
     */
    protected $description = 'Sync order status with delivery status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting order status synchronization...');

        // Get orders dengan delivery status delivered tapi order status belum delivered
        $ordersToUpdate = Order::with('delivery')
            ->whereHas('delivery', function($query) {
                $query->where('status', 'delivered');
            })
            ->where('status', '!=', 'delivered')
            ->get();

        if ($ordersToUpdate->isEmpty()) {
            $this->info('No orders need status sync.');
            return 0;
        }

        $updated = 0;
        foreach ($ordersToUpdate as $order) {
            $oldStatus = $order->status;
            $order->update(['status' => 'delivered']);
            
            $this->line("Updated Order #{$order->order_number}: {$oldStatus} -> delivered");
            $updated++;
        }

        $this->info("Successfully synchronized {$updated} orders.");
        return 0;
    }
}