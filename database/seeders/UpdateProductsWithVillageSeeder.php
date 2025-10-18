<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Region;
use App\Models\User;

class UpdateProductsWithVillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some villages
        $villages = Region::desa()->take(3)->get();
        
        if ($villages->count() == 0) {
            $this->command->error('No villages found. Please run RegionSeeder first.');
            return;
        }
        
        // Update first 3 products with village information
        $products = Product::take(3)->get();
        
        foreach ($products as $index => $product) {
            if (isset($villages[$index])) {
                $product->update([
                    'village_id' => $villages[$index]->id
                ]);
                
                $this->command->info("Updated product '{$product->name}' with village '{$villages[$index]->name}'");
            }
        }
        
        // Also ensure users (producers) have phone numbers for testing
        $producers = User::where('user_type', 'produsen')->take(3)->get();
        foreach ($producers as $index => $producer) {
            if (empty($producer->phone)) {
                $producer->update([
                    'phone' => '0812345678' . ($index + 1)
                ]);
                $this->command->info("Added phone number to producer '{$producer->name}'");
            }
        }
    }
}