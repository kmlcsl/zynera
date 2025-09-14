<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek dulu apakah tabel sudah ada
        if (!Schema::hasTable('user_permissions')) {
            Schema::create('user_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('route_name');
                $table->string('menu_title')->nullable();
                $table->boolean('is_allowed')->default(true);
                $table->timestamps();

                // Index untuk performa
                $table->index(['user_id', 'route_name']);
                $table->unique(['user_id', 'route_name'], 'user_route_unique');
            });

            // Seeder otomatis untuk user yang sudah ada
            $this->seedDefaultPermissions();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }

    /**
     * Seed default permissions untuk user yang sudah ada
     */
    private function seedDefaultPermissions(): void
    {
        // Import class yang dibutuhkan
        $userModel = app(\App\Models\User::class);

        // Ambil semua user admin panel (bukan konsumen)
        $users = $userModel::whereIn('user_type', ['admin', 'produsen', 'kurir'])->get();

        if ($users->count() > 0) {
            // Import ListRoutes
            $listRoutes = new \App\Classes\ListRoutes();

            foreach ($users as $user) {
                // Dapatkan routes berdasarkan user type
                $allowedRoutes = $listRoutes->getRoutesByUserType($user->user_type);

                foreach ($allowedRoutes as $menu) {
                    foreach ($menu['item'] as $route) {
                        if (!empty($route['name']) && $listRoutes->getIgnoreType($route['type'])) {
                            // Insert permission default (allowed)
                            DB::table('user_permissions')->insertOrIgnore([
                                'user_id' => $user->id,
                                'route_name' => $route['name'],
                                'menu_title' => $route['title'],
                                'is_allowed' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }
};
