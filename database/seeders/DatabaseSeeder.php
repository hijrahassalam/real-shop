<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for receiving notifications
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample products with realistic e-commerce data
        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life.',
                'price' => 149.99,
                'stock_quantity' => 25,
            ],
            [
                'name' => 'Laptop Stand Aluminum',
                'description' => 'Ergonomic aluminum laptop stand for better posture and cooling.',
                'price' => 49.99,
                'stock_quantity' => 50,
            ],
            [
                'name' => 'USB-C Hub 7-in-1',
                'description' => 'Multi-port USB-C hub with HDMI, USB 3.0, SD card reader, and power delivery.',
                'price' => 39.99,
                'stock_quantity' => 3, // Low stock for testing notification
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'description' => 'Full-size mechanical keyboard with RGB backlighting and hot-swappable switches.',
                'price' => 89.99,
                'stock_quantity' => 15,
            ],
            [
                'name' => 'Wireless Mouse Ergonomic',
                'description' => 'Ergonomic wireless mouse with adjustable DPI and silent clicks.',
                'price' => 29.99,
                'stock_quantity' => 40,
            ],
            [
                'name' => '4K Webcam Pro',
                'description' => 'Professional 4K webcam with auto-focus and built-in microphone.',
                'price' => 129.99,
                'stock_quantity' => 2, // Low stock for testing notification
            ],
            [
                'name' => 'Monitor Light Bar',
                'description' => 'LED monitor light bar with adjustable brightness and color temperature.',
                'price' => 59.99,
                'stock_quantity' => 20,
            ],
            [
                'name' => 'Desk Mat XXL',
                'description' => 'Extra large desk mat with stitched edges and non-slip base.',
                'price' => 24.99,
                'stock_quantity' => 60,
            ],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'low_stock_threshold' => 5,
                'is_active' => true,
            ]));
        }

        // Create additional random products
        Product::factory(12)->create();
    }
}
