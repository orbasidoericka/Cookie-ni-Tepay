<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Headphones',
                'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life.',
                'price' => 149.99,
                'stock' => 25,
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Feature-packed smartwatch with health tracking, GPS, and water resistance.',
                'price' => 299.99,
                'stock' => 15,
            ],
            [
                'name' => 'Laptop Stand',
                'description' => 'Ergonomic aluminum laptop stand for better posture and cooling.',
                'price' => 49.99,
                'stock' => 50,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with Cherry MX switches for gaming and typing.',
                'price' => 129.99,
                'stock' => 30,
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, SD card reader, and power delivery.',
                'price' => 59.99,
                'stock' => 40,
            ],
            [
                'name' => 'Webcam HD',
                'description' => '1080p HD webcam with built-in microphone and auto-focus for video calls.',
                'price' => 79.99,
                'stock' => 35,
            ],
            [
                'name' => 'Portable Charger',
                'description' => '20000mAh portable power bank with fast charging support for all devices.',
                'price' => 39.99,
                'stock' => 60,
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with precision tracking and long battery life.',
                'price' => 34.99,
                'stock' => 45,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
