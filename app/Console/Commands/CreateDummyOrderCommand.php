<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;

class CreateDummyOrderCommand extends Command
{
    protected $signature = 'create:dummy-order {order_id} {user_email}';
    protected $description = 'Create dummy order for testing authorization';

    public function handle()
    {
        $orderId = $this->argument('order_id');
        $userEmail = $this->argument('user_email');

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User with email {$userEmail} not found!");
            return;
        }

        // Check if order already exists
        if (Order::find($orderId)) {
            $this->error("Order {$orderId} already exists!");
            return;
        }

        $order = Order::create([
            'id' => $orderId,
            'order_number' => 'GF' . date('Ymd') . strtoupper(\Illuminate\Support\Str::random(6)),
            'user_id' => $user->id,
            'total_amount' => 75000,
            'subtotal' => 70000,
            'service_fee' => 1400,
            'shipping_cost' => 5000,
            'total_items' => 2,
            'status' => 'pending',
            'recipient_name' => $user->name,
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jalan Testing No. 123, Jakarta',
            'notes' => 'Dummy order for testing'
        ]);

        // Create payment record
        Payment::create([
            'order_id' => $order->id,
            'method' => 'transfer',
            'amount' => $order->total_amount,
            'status' => 'pending'
        ]);

        $this->info("✅ Dummy order created successfully!");
        $this->line("   - Order ID: {$order->id}");
        $this->line("   - Order Number: {$order->order_number}");
        $this->line("   - User: {$user->email}");
        $this->line("   - Total: Rp " . number_format($order->total_amount, 0, ',', '.'));
    }
}
