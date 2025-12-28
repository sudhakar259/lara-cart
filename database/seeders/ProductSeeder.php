<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium wireless headphones with noise cancellation',
                'price' => 129.99,
                'stock_quantity' => 50,
            ],
            [
                'name' => 'USB-C Cable',
                'description' => 'Durable USB-C charging and data cable',
                'price' => 19.99,
                'stock_quantity' => 200,
            ],
            [
                'name' => 'Phone Case',
                'description' => 'Protective phone case with premium materials',
                'price' => 24.99,
                'stock_quantity' => 150,
            ],
            [
                'name' => 'Screen Protector',
                'description' => 'Tempered glass screen protector',
                'price' => 9.99,
                'stock_quantity' => 300,
            ],
            [
                'name' => 'Portable Charger',
                'description' => '20000mAh portable power bank',
                'price' => 49.99,
                'stock_quantity' => 75,
            ],
            [
                'name' => 'Desk Lamp',
                'description' => 'LED desk lamp with adjustable brightness',
                'price' => 39.99,
                'stock_quantity' => 40,
            ],
            [
                'name' => 'Keyboard',
                'description' => 'Mechanical gaming keyboard with RGB lighting',
                'price' => 89.99,
                'stock_quantity' => 30,
            ],
            [
                'name' => 'Mouse Pad',
                'description' => 'Large gaming mouse pad with non-slip base',
                'price' => 29.99,
                'stock_quantity' => 60,
            ],
            [
                'name' => 'HDMI Cable',
                'description' => 'High-speed HDMI 2.1 cable',
                'price' => 14.99,
                'stock_quantity' => 100,
            ],
            [
                'name' => 'Monitor Stand',
                'description' => 'Adjustable monitor stand with storage',
                'price' => 59.99,
                'stock_quantity' => 25,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
