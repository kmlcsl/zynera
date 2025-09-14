<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserPermission;
use App\Classes\ListRoutes;

class GenerateUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'permissions:generate {--fresh : Delete existing permissions and regenerate}';

    /**
     * The console command description.
     */
    protected $description = 'Generate default permissions for all admin panel users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Generating user permissions...');

        // Check if fresh flag is used
        if ($this->option('fresh')) {
            $this->warn('🗑️ Deleting existing permissions...');
            UserPermission::truncate();
        }

        // Get all admin panel users
        $users = User::whereIn('user_type', ['admin', 'produsen', 'kurir'])->get();

        if ($users->isEmpty()) {
            $this->error('❌ No admin panel users found!');
            return 1;
        }

        $this->info("👥 Found {$users->count()} admin panel users");

        // Initialize ListRoutes
        $listRoutes = new ListRoutes();
        $progressBar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            $this->generatePermissionsForUser($user, $listRoutes);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info('✅ User permissions generated successfully!');

        // Show summary
        $this->showSummary();

        return 0;
    }

    /**
     * Generate permissions for specific user
     */
    private function generatePermissionsForUser(User $user, ListRoutes $listRoutes)
    {
        $allowedRoutes = $listRoutes->getRoutesByUserType($user->user_type);
        $permissionCount = 0;

        foreach ($allowedRoutes as $menu) {
            foreach ($menu['item'] as $route) {
                if (!empty($route['name']) && $listRoutes->getIgnoreType($route['type'])) {
                    // Check if permission already exists
                    $exists = UserPermission::where('user_id', $user->id)
                        ->where('route_name', $route['name'])
                        ->exists();

                    if (!$exists || $this->option('fresh')) {
                        UserPermission::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'route_name' => $route['name']
                            ],
                            [
                                'menu_title' => $route['title'],
                                'is_allowed' => true
                            ]
                        );
                        $permissionCount++;
                    }
                }
            }
        }

        $this->newLine();
        $this->line("  📝 {$user->name} ({$user->user_type}): {$permissionCount} permissions");
    }

    /**
     * Show permissions summary
     */
    private function showSummary()
    {
        $this->info('📊 SUMMARY:');
        $this->newLine();

        // Count by user type
        $userTypes = ['admin', 'produsen', 'kurir'];

        foreach ($userTypes as $type) {
            $userCount = User::where('user_type', $type)->count();
            $permissionCount = UserPermission::whereHas('user', function ($q) use ($type) {
                $q->where('user_type', $type);
            })->count();

            if ($userCount > 0) {
                $this->line("  🔸 {$type}: {$userCount} users, {$permissionCount} permissions");
            }
        }

        $this->newLine();
        $totalPermissions = UserPermission::count();
        $allowedPermissions = UserPermission::where('is_allowed', true)->count();
        $deniedPermissions = UserPermission::where('is_allowed', false)->count();

        $this->line("  📈 Total Permissions: {$totalPermissions}");
        $this->line("  ✅ Allowed: {$allowedPermissions}");
        $this->line("  ❌ Denied: {$deniedPermissions}");
    }
}
