<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Hasil Tani', 'slug' => 'hasil-tani'],
            ['name' => 'Hasil Laut', 'slug' => 'hasil-laut'],
            ['name' => 'Makanan Olahan', 'slug' => 'makanan-olahan'],
            ['name' => 'Minuman Tradisional', 'slug' => 'minuman-tradisional'],
            ['name' => 'Rempah-rempah', 'slug' => 'rempah-rempah'],
            ['name' => 'Kue Tradisional', 'slug' => 'kue-tradisional'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
