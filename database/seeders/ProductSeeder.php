<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📦 Seeding products...');

        // Get existing category IDs to avoid additional queries
        $categoryIds = Category::pluck('id')->toArray();

        // Create products in chunks for better memory management
        $chunks = 100;
        $totalProducts = 500;

        for ($i = 0; $i < $totalProducts / $chunks; $i++) {
            Product::factory($chunks)->create([
                'category_id' => fake()->randomElement($categoryIds)
            ]);

            if ($i % 10 === 0) {
                $this->command->info("Created " . (($i + 1) * $chunks) . " products...");
            }
        }

        $this->command->info("✅ Created {$totalProducts} products");
    }
}
