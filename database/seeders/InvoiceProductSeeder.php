<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔗 Seeding invoice-product relationships...');

        $invoiceIds = Invoice::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        // Pre-load all product prices to avoid individual queries
        $productPrices = Product::pluck('price', 'id')->toArray();

        $batchSize = 10000;
        $invoiceProductData = [];

        foreach ($invoiceIds as $index => $invoiceId) {
            // Each invoice gets 1-10 products
            $numProducts = rand(1, 10);
            $selectedProducts = fake()->randomElements($productIds, $numProducts);

            foreach ($selectedProducts as $productId) {
                // Get the product's current price as base price from pre-loaded array
                $basePrice = $productPrices[$productId];
                // Add some variation to the price (±20%)
                $variation = $basePrice * (rand(80, 120) / 100);

                $invoiceProductData[] = [
                    'invoice_id' => $invoiceId,
                    'product_id' => $productId,
                    'quantity' => rand(1, 10),
                    'price' => round($variation, 2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insert in batches for better performance
                if (count($invoiceProductData) >= $batchSize) {
                    DB::table('invoice_product')->insert($invoiceProductData);
                    $invoiceProductData = [];

                    if ($index % 10000 === 0) {
                        $this->command->info("Processed " . ($index + 1) . " invoices...");
                    }
                }
            }
        }

        // Insert any remaining data
        if (!empty($invoiceProductData)) {
            DB::table('invoice_product')->insert($invoiceProductData);
        }

        $totalInvoiceProducts = DB::table('invoice_product')->count();
        $this->command->info("✅ Created {$totalInvoiceProducts} invoice-product relationships");
    }
}
