<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ZyneraMainAccountsSeeder extends Seeder
{
    public function run()
    {
        echo "=== CREATING/UPDATING ZYNERA MAIN ACCOUNTS ===\n";

        $accounts = [
            [
                'name' => 'Administrator Zynera',
                'email' => 'admin@zynera.com',
                'phone' => '+62812-3456-7890',
                'address' => 'Kantor Pusat Zynera, Jakarta',
                'user_type' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Penjual Minyak Zynera',
                'email' => 'penjual@zynera.com',
                'phone' => '+62813-4567-8901',
                'address' => 'Supplier Minyak Jelantah Jakarta',
                'user_type' => 'produsen',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kurir Pengiriman Zynera',
                'email' => 'kurir@zynera.com',
                'phone' => '+62814-5678-9012',
                'address' => 'Driver Zynera Express',
                'user_type' => 'kurir',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Pembeli Zynera',
                'email' => 'pembeli@zynera.com',
                'phone' => '+62815-6789-0123',
                'address' => 'Customer Zynera Indonesia',
                'user_type' => 'konsumen',
                'password' => Hash::make('password'),
            ]
        ];

        foreach ($accounts as $account) {
            // Check if user exists
            $existingUser = DB::table('users')->where('email', $account['email'])->first();
            
            if ($existingUser) {
                // Update existing user
                DB::table('users')
                    ->where('email', $account['email'])
                    ->update([
                        'name' => $account['name'],
                        'password' => $account['password'],
                        'phone' => $account['phone'],
                        'address' => $account['address'],
                        'user_type' => $account['user_type'],
                        'is_verified' => 1,
                        'email_verified_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                
                echo "✅ Updated: {$account['email']} (Password: password)\n";
            } else {
                // Create new user
                DB::table('users')->insert([
                    'name' => $account['name'],
                    'email' => $account['email'],
                    'password' => $account['password'],
                    'phone' => $account['phone'],
                    'address' => $account['address'],
                    'user_type' => $account['user_type'],
                    'is_verified' => 1,
                    'email_verified_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                
                echo "✅ Created: {$account['email']} (Password: password)\n";
            }
        }

        echo "\n🎉 SELESAI! Semua akun utama Zynera siap digunakan dengan password: 'password'\n";
        echo "==========================================\n";
        echo "📧 Login Credentials:\n";
        echo "  Admin:    admin@zynera.com / password\n";
        echo "  Penjual:  penjual@zynera.com / password\n";
        echo "  Kurir:    kurir@zynera.com / password\n";
        echo "  Pembeli:  pembeli@zynera.com / password\n";
        echo "==========================================\n";
    }
}