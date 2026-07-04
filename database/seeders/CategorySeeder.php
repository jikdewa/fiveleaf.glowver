<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'category_name' => 'Keychain',
                'description' => 'Produk gantungan kunci'
            ],

            [
                'category_name' => 'Sticker',
                'description' => 'Produk stiker'
            ],

            [
                'category_name' => 'Case Korek',
                'description' => 'Case dan pelindung korek'
            ],

            [
                'category_name' => 'Lanyard',
                'description' => 'Produk lanyard'
            ],

            [
                'category_name' => 'Tumbler',
                'description' => 'Produk tumbler'
            ],

            [
                'category_name' => 'Mug',
                'description' => 'Produk mug'
            ],

            [
                'category_name' => 'Acrylic',
                'description' => 'Produk acrylic'
            ],

            [
                'category_name' => 'Anime',
                'description' => 'Kategori anime'
            ],

            [
                'category_name' => 'Gaming',
                'description' => 'Kategori gaming'
            ],

            [
                'category_name' => 'Custom',
                'description' => 'Produk custom'
            ],

        ];

        foreach ($categories as $category) {

            Category::firstOrCreate(
                [
                    'category_name' => $category['category_name']
                ],
                [
                    'description' => $category['description']
                ]
            );

        }
    }
}