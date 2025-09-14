<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin AgriConnect',
            'email' => 'admin@agriconnect.click',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'is_verified' => true,
        ]);
        $admin->assignRole('admin');

        // Sample producer
        $producer = User::create([
            'name' => 'Petani Aceh',
            'email' => 'petani@agriconnect.click',
            'password' => Hash::make('petani123'),
            'user_type' => 'produsen',
            'phone' => '081234567890',
            'address' => 'Desa Meulaboh, Aceh Barat',
            'village' => 'Meulaboh',
            'district' => 'Aceh Barat',
            'is_verified' => true,
        ]);
        $producer->assignRole('produsen');

        // Sample courier
        $courier = User::create([
            'name' => 'Kurir Gampong',
            'email' => 'kurir@agriconnect.click',
            'password' => Hash::make('kurir123'),
            'user_type' => 'kurir',
            'phone' => '081234567891',
            'address' => 'Kota Meulaboh, Aceh Barat',
            'is_verified' => true,
        ]);
        $courier->assignRole('kurir');

        $customer = User::create([
            'name' => 'Konsumen Testing',
            'email' => 'konsumen@agriconnect.click',
            'password' => Hash::make('konsumen123'),
            'user_type' => 'konsumen',
            'phone' => '081234567800',
            'address' => 'Kota Meulaboh, Aceh Barat',
            'is_verified' => true,
        ]);
        $customer->assignRole('konsumen');
    }
}
