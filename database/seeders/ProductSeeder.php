<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::create([
            'product_code' => 'PRD001',
            'product_name' => 'Naruto Acrylic Keychain',
            'description' => 'Keychain acrylic Naruto',
            'cost_price' => 5000,
            'selling_price' => 10000,
            'stock' => 100,
            'minimum_stock' => 10,
        ]);

        $categoryIds = Category::whereIn(
            'category_name',
            [
                'Keychain',
                'Anime',
                'Acrylic'
            ]
        )->pluck('id');

        $product->categories()->sync($categoryIds);
    }
}
// class ProductSeeder extends Seeder
// {
//     // public function run(): void
//     // {
//     //     $categoryIds = Category::pluck('id')->toArray();

//     //     for ($i = 1; $i <= 100; $i++) {

//     //         $costPrice = rand(5000, 50000);

//     //         Product::create([
//     //             'category_id' => $categoryIds[array_rand($categoryIds)],

//     //             'product_code' => 'PRD' . str_pad($i, 4, '0', STR_PAD_LEFT),

//     //             'barcode' => '899' . rand(1000000000, 9999999999),

//     //             'product_name' => 'Produk ' . $i,

//     //             'description' => 'Deskripsi Produk ' . $i,

//     //             'cost_price' => $costPrice,

//     //             'selling_price' => $costPrice + rand(1000, 20000),

//     //             'stock' => rand(0, 200),

//     //             'minimum_stock' => rand(5, 20),

//     //             'product_photo' => 'products/default.jpg',

//     //             'barcode_photo' => 'barcodes/default.png',

//     //             'is_active' => rand(0, 100) > 10, // 90% aktif
//     //         ]);
//     //     }
//     // }
// }