<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $producerRole = Role::create(['name' => 'produsen']);
        $consumerRole = Role::create(['name' => 'konsumen']);
        $courierRole = Role::create(['name' => 'kurir']);

        // Create permissions
        $permissions = [
            'manage_users',
            'manage_products',
            'manage_orders',
            'manage_categories',
            'manage_articles',
            'manage_nutrition_labels',
            'view_dashboard',
            'manage_deliveries',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo($permissions);
        $producerRole->givePermissionTo(['manage_products', 'view_dashboard']);
        $courierRole->givePermissionTo(['manage_deliveries', 'view_dashboard']);
    }
}
