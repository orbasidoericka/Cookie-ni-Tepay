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
        // Only seed if products table is empty
        if (Product::count() > 0) {
            $this->command->info('Products already exist. Skipping seeding.');
            return;
        }

        $products = [
            [
                'name' => 'Butter Croissant',
                'description' => 'Flaky, buttery croissant with golden layers. Baked fresh daily with premium French butter.',
                'price' => 65.00,
                'stock' => 30,
            ],
            [
                'name' => 'Chocolate Danish',
                'description' => 'Rich chocolate filling wrapped in delicate pastry, topped with chocolate drizzle.',
                'price' => 75.00,
                'stock' => 25,
            ],
            [
                'name' => 'Blueberry Muffin',
                'description' => 'Moist vanilla muffin loaded with fresh blueberries and a sweet crumb topping.',
                'price' => 55.00,
                'stock' => 40,
            ],
            [
                'name' => 'Cinnamon Roll',
                'description' => 'Soft, gooey cinnamon roll with cream cheese frosting. A sweet breakfast favorite.',
                'price' => 80.00,
                'stock' => 20,
            ],
            [
                'name' => 'Almond Croissant',
                'description' => 'Butter croissant filled with sweet almond cream and topped with sliced almonds.',
                'price' => 85.00,
                'stock' => 18,
            ],
            [
                'name' => 'Apple Turnover',
                'description' => 'Flaky puff pastry filled with spiced apple filling and dusted with cinnamon sugar.',
                'price' => 60.00,
                'stock' => 28,
            ],
            [
                'name' => 'Lemon Tart',
                'description' => 'Tangy lemon curd in a buttery shortbread crust, topped with toasted meringue.',
                'price' => 95.00,
                'stock' => 15,
            ],
            [
                'name' => 'Chocolate Chip Scone',
                'description' => 'Tender scone studded with chocolate chips. Perfect with your morning coffee.',
                'price' => 58.00,
                'stock' => 35,
            ],
            [
                'name' => 'Malunggay Pandesal',
                'description' => 'Traditional Filipino bread roll infused with nutritious malunggay leaves. Soft and healthy.',
                'price' => 50.00,
                'stock' => 50,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
