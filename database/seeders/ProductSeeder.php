<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user produsen (user_id = 2 dari UserSeeder)
        $producer = User::where('user_type', 'produsen')->first();

        if (!$producer) {
            $this->command->error('Produsen tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $products = [
            [
                'name' => 'Beras Pandan Wangi',
                'slug' => 'beras-pandan-wangi',
                'description' => 'Beras premium dari sawah organik Aceh Barat dengan aroma pandan yang khas. Tumbuh tanpa pestisida, memberikan nutrisi terbaik untuk keluarga.',
                'price' => 85000,
                'stock' => 50,
                'unit' => 'kg',
                'images' => json_encode(['products/beras-pandan.jpg']),
                'category_id' => 1, // Hasil Tani
                'user_id' => $producer->id,
                'is_active' => true,
                'is_featured' => true,
                'weight' => 1.0,
                'ingredients' => json_encode(['Beras organik', 'Pandan alami']),
                'expired_date' => now()->addMonths(6),
            ],
            [
                'name' => 'Ikan Kembung Segar',
                'slug' => 'ikan-kembung-segar',
                'description' => 'Ikan kembung segar hasil tangkapan nelayan Pantai Barat Aceh. Ditangkap pagi hari dan langsung dikirim untuk menjaga kesegaran.',
                'price' => 45000,
                'stock' => 25,
                'unit' => 'kg',
                'images' => json_encode(['products/ikan-kembung.jpg']),
                'category_id' => 2, // Hasil Laut
                'user_id' => $producer->id,
                'is_active' => true,
                'is_featured' => true,
                'weight' => 1.0,
                'ingredients' => json_encode(['Ikan kembung segar']),
                'expired_date' => now()->addDays(3),
            ],
            [
                'name' => 'Keripik Pisang Aceh',
                'slug' => 'keripik-pisang-aceh',
                'description' => 'Keripik pisang renyah dengan bumbu tradisional Aceh. Dibuat dari pisang pilihan yang digoreng dengan minyak kelapa murni.',
                'price' => 25000,
                'stock' => 100,
                'unit' => 'bungkus',
                'images' => json_encode(['products/keripik-pisang.jpg']),
                'category_id' => 3, // Makanan Olahan
                'user_id' => $producer->id,
                'is_active' => true,
                'is_featured' => false,
                'weight' => 0.25,
                'ingredients' => json_encode(['Pisang', 'Minyak kelapa', 'Garam', 'Bumbu tradisional']),
                'expired_date' => now()->addMonths(3),
            ],
            [
                'name' => 'Es Timun Serut',
                'slug' => 'es-timun-serut',
                'description' => 'Minuman tradisional Aceh yang menyegarkan. Terbuat dari timun segar, santan, dan gula aren asli yang memberikan rasa manis alami.',
                'price' => 15000,
                'stock' => 30,
                'unit' => 'gelas',
                'images' => json_encode(['products/es-timun-serut.jpg']),
                'category_id' => 4, // Minuman Tradisional
                'user_id' => $producer->id,
                'is_active' => true,
                'is_featured' => false,
                'weight' => 0.5,
                'ingredients' => json_encode(['Timun segar', 'Santan', 'Gula aren', 'Es batu']),
                'expired_date' => now()->addDays(1),
            ],
            [
                'name' => 'Bumbu Gulai Aceh',
                'slug' => 'bumbu-gulai-aceh',
                'description' => 'Racikan bumbu gulai khas Aceh yang sudah dihaluskan. Terdiri dari rempah-rempah pilihan seperti kemiri, kunyit, dan cabai merah.',
                'price' => 35000,
                'stock' => 75,
                'unit' => 'pack',
                'images' => json_encode(['products/bumbu-gulai.jpg']),
                'category_id' => 5, // Rempah-rempah
                'user_id' => $producer->id,
                'is_active' => true,
                'is_featured' => true,
                'weight' => 0.3,
                'ingredients' => json_encode(['Kemiri', 'Kunyit', 'Cabai merah', 'Bawang merah', 'Bawang putih', 'Jahe', 'Lengkuas']),
                'expired_date' => now()->addMonths(12),
            ],
            [
                'name' => 'Kue Bhoi',
                'slug' => 'kue-bhoi',
                'description' => 'Kue tradisional Aceh yang lembut dan manis. Dibuat dari tepung beras dengan santan dan gula merah, dikukus dengan daun pisang.',
                'price' => 20000,
                'stock' => 40,
                'unit' => 'bungkus',
                'images' => json_encode(['products/kue-bhoi.jpg']),
                'category_id' => 6, // Kue Tradisional
                'user_id' => $producer->id,
                'is_active' => true,
                'is_featured' => false,
                'weight' => 0.4,
                'ingredients' => json_encode(['Tepung beras', 'Santan', 'Gula merah', 'Garam', 'Daun pisang']),
                'expired_date' => now()->addDays(5),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Sample products untuk setiap kategori berhasil dibuat!');
    }
}
