<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Journal;
use App\Models\JournalLine;
use App\Models\Organisation;
use App\Models\Role;
use App\Models\User;
use App\Models\XeroLog;
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
            ]
        );
        $admin->syncRoles([$adminRole]);
        $this->command->info('Admin user created: admin@example.com / password');

        // Create Regular Users
        $users = User::factory(5)->create();
        foreach ($users as $user) {
            $user->syncRoles([$userRole]);
        }
        $this->command->info('Created 5 test users.');

        // Create Organisations
        $organisations = Organisation::factory(10)->create();
        $this->command->info('Created 10 test organisations.');

        // Create Journals and Journal Lines for each organisation
        foreach ($organisations as $organisation) {
            $journals = Journal::factory(rand(5, 15))->create([
                'organisation_id' => $organisation->id,
            ]);

            foreach ($journals as $journal) {
                JournalLine::factory(rand(2, 8))->create([
                    'journal_id' => $journal->id,
                ]);
            }

            // Create Logs for each organisation
            XeroLog::factory(rand(10, 30))->create([
                'organisation_id' => $organisation->id,
            ]);
        }
        $this->command->info('Created journals, journal lines, and logs for organisations.');

        // Create some logs without organisation
        XeroLog::factory(5)->create([
            'organisation_id' => null,
        ]);
        $this->command->info('Created 5 logs without organisation.');

        $this->command->info('Test data seeding completed!');
    }
}

