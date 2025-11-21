<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Organisation;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Seed test data for development and testing environments.
     */
    public function run(): void
    {
        $this->command->info('Seeding test data...');

        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'organisation_id' => null,
            ]
        );
        $admin->syncRoles([$adminRole]);
        $this->command->info('Admin user created: admin@example.com / password');

        // Create Organisations
        $organisations = Organisation::factory(5)->create();
        $this->command->info('Created 5 test organisations.');

        // Create Regular Users and assign to organisations
        foreach ($organisations as $index => $organisation) {
            $user = User::factory()->create([
                'email' => "user{$index}@example.com",
                'password' => Hash::make('password'),
                'organisation_id' => $organisation->id,
            ]);
            $user->syncRoles([$userRole]);
            $this->command->info("Created user: user{$index}@example.com / password (assigned to {$organisation->name})");

            // Create sample reports for each organisation
            Report::create([
                'organisation_id' => $organisation->id,
                'name' => 'Sample Sales Report',
                'description' => 'A sample report showing sales data',
                'sql_query' => 'SELECT * FROM sales LIMIT 100',
                'visualization_type' => 'table',
                'created_by' => $admin->id,
                'is_active' => true,
            ]);

            Report::create([
                'organisation_id' => $organisation->id,
                'name' => 'Monthly Revenue Chart',
                'description' => 'Monthly revenue breakdown',
                'sql_query' => 'SELECT month, SUM(revenue) as total FROM sales GROUP BY month',
                'visualization_type' => 'chart',
                'chart_config' => [
                    'type' => 'bar',
                    'label_column' => 'month',
                    'data_column' => 'total',
                ],
                'created_by' => $admin->id,
                'is_active' => true,
            ]);
        }
        $this->command->info('Created sample reports for each organisation.');

        $this->command->info('Test data seeding completed!');
        $this->command->info('');
        $this->command->info('Test Users Created:');
        $this->command->info('  Admin: admin@example.com / password');
        for ($i = 0; $i < 5; $i++) {
            $this->command->info("  User:  user{$i}@example.com / password");
        }
    }
}

