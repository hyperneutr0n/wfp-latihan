<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🧾 Seeding invoices...');

        // Get existing IDs to avoid additional queries
        $employeeIds = Employee::pluck('id')->toArray();
        $customerIds = Customer::pluck('id')->toArray();

        $totalInvoices = 500000;
        $chunkSize = 1000;

        for ($i = 0; $i < $totalInvoices / $chunkSize; $i++) {
            Invoice::factory($chunkSize)->create([
                'employee_id' => fake()->randomElement($employeeIds),
                'customer_id' => fake()->randomElement($customerIds)
            ]);

            if ($i % 50 === 0) {
                $this->command->info("Created " . (($i + 1) * $chunkSize) . " invoices...");
            }
        }

        $this->command->info("✅ Created {$totalInvoices} invoices");
    }
}
