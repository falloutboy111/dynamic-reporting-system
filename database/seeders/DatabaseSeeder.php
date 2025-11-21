<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Only create test data in non-production environments
        if (app()->environment(['local', 'testing'])) {
            $this->call([
                TestDataSeeder::class,
            ]);
        }
    }
}
