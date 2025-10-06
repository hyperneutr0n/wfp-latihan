<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 Seeding customers...');

        $totalCustomers = 100000;
        $chunkSize = 1000;

        for ($i = 0; $i < $totalCustomers / $chunkSize; $i++) {
            Customer::factory($chunkSize)->create();

            if ($i % 10 === 0) {
                $this->command->info("Created " . (($i + 1) * $chunkSize) . " customers...");
            }
        }

        $this->command->info("✅ Created {$totalCustomers} customers");
    }
}
