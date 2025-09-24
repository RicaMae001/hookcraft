<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // ✅ put this outside the class

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Rose Bouquet',
            'description' => 'Beautiful red roses for any occasion.',
            'price' => 1500,
            'category' => 'Bouquets',
            'image' => 'rose.jpg',
            'stock' => 10,
        ]);

        Product::create([
            'name' => 'Tulip Arrangement',
            'description' => 'Fresh tulips wrapped with care.',
            'price' => 1800,
            'category' => 'Bouquets',
            'image' => 'tulip.jpg',
            'stock' => 8,
        ]);
    }
}
