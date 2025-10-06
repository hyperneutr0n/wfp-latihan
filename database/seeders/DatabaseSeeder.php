<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Disable foreign key checks for better performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Run seeders in dependency order
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            EmployeeSeeder::class,
            CustomerSeeder::class,
            InvoiceSeeder::class,
            InvoiceProductSeeder::class,
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('🎉 Database seeding completed successfully!');
    }
}
