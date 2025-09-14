<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DebugAuthorizationCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'debug:authorization {order_id?}';

    /**
     * The console command description.
     */
    protected $description = 'Debug authorization issues for orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 DEBUGGING AUTHORIZATION ISSUES');
        $this->line('=====================================');

        $orderId = $this->argument('order_id');

        if ($orderId) {
            $this->debugSpecificOrder($orderId);
        } else {
            $this->debugGeneralStats();
        }
    }

    private function debugSpecificOrder($orderId)
    {
        $this->info("🔍 Debugging Order ID: {$orderId}");
        $this->line('----------------------------------');

        $order = Order::with(['user', 'orderItems.product'])->find($orderId);

        if (!$order) {
            $this->error("❌ Order {$orderId} not found!");
            return;
        }

        $this->info("✅ Order found:");
        $this->line("   - Order ID: {$order->id}");
        $this->line("   - Order Number: {$order->order_number}");
        $this->line("   - User ID: {$order->user_id}");
        $this->line("   - User Email: {$order->user->email}");
        $this->line("   - User Type: {$order->user->user_type}");
        $this->line("   - Status: {$order->status}");
        $this->line("   - Created: {$order->created_at}");
        $this->line("   - Total Amount: Rp " . number_format($order->total_amount, 0, ',', '.'));

        $this->line('');
        $this->info("🔍 Authorization Check Results:");
        
        // Test different user scenarios
        $this->testUserCanAccess($order, $order->user_id, "Owner");
        
        // Test admin access
        $admin = User::where('user_type', 'admin')->first();
        if ($admin) {
            $this->testUserCanAccess($order, $admin->id, "Admin");
        }

        // Test other user access
        $otherUser = User::where('user_type', 'konsumen')
                         ->where('id', '!=', $order->user_id)
                         ->first();
        if ($otherUser) {
            $this->testUserCanAccess($order, $otherUser->id, "Other User");
        }
    }

    private function testUserCanAccess($order, $userId, $userType)
    {
        $canAccess = $order->user_id === $userId;
        $status = $canAccess ? "✅ ALLOWED" : "❌ DENIED";
        
        $this->line("   - {$userType} (ID: {$userId}): {$status}");
    }

    private function debugGeneralStats()
    {
        $this->info("📊 General Authorization Statistics");
        $this->line('----------------------------------');

        $totalOrders = Order::count();
        $totalUsers = User::count();
        
        $userTypes = User::select('user_type', DB::raw('count(*) as count'))
                        ->groupBy('user_type')
                        ->get();

        $orderStatuses = Order::select('status', DB::raw('count(*) as count'))
                             ->groupBy('status')
                             ->get();

        $this->line("📈 Overall Stats:");
        $this->line("   - Total Orders: {$totalOrders}");
        $this->line("   - Total Users: {$totalUsers}");

        $this->line('');
        $this->line("👥 User Types:");
        foreach ($userTypes as $type) {
            $this->line("   - {$type->user_type}: {$type->count} users");
        }

        $this->line('');
        $this->line("📦 Order Statuses:");
        foreach ($orderStatuses as $status) {
            $this->line("   - {$status->status}: {$status->count} orders");
        }

        // Recent problematic orders
        $recentOrders = Order::with('user')
                            ->latest()
                            ->limit(5)
                            ->get();

        $this->line('');
        $this->line("🔍 Recent Orders (last 5):");
        foreach ($recentOrders as $order) {
            $this->line("   - ID: {$order->id}, User: {$order->user->email}, Status: {$order->status}");
        }

        $this->line('');
        $this->comment("💡 To debug specific order: php artisan debug:authorization {order_id}");
    }
}
