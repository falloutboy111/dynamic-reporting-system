<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Journal;
use App\Models\JournalLine;
use App\Models\Organisation;
use App\Models\Role;
use App\Models\User;
use App\Models\XeroLog;
use Database\Seeders\ProductionDataSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SeedProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-production 
                            {--force : Force the operation to run even in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all database seeders in production (excluding RoleSeeder which should already be run)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting production seeding...');

        // RoleSeeder already run - skip it
        // $this->info('Seeding roles...');
        // Role::firstOrCreate(
        //     ['name' => 'admin', 'guard_name' => 'web'],
        //     ['name' => 'admin', 'guard_name' => 'web']
        // );
        // Role::firstOrCreate(
        //     ['name' => 'user', 'guard_name' => 'web'],
        //     ['name' => 'user', 'guard_name' => 'web']
        // );
        // $this->info('✓ Roles seeded successfully.');

        // Run TestDataSeeder logic
        $this->info('Seeding test data...');
        $this->seedTestData();

        $this->info('');
        $this->info('Production seeding completed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Seed test data (from TestDataSeeder).
     */
    protected function seedTestData(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        if (!$adminRole || !$userRole) {
            $this->error('Roles not found! Please ensure RoleSeeder has been run.');
            return;
        }

        // Create Admin User if doesn't exist
        $adminExists = User::where('email', 'admin@example.com')->exists();
        if (!$adminExists) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
            $admin->syncRoles([$adminRole]);
            $this->info('Admin user created: admin@example.com / password');
        } else {
            $this->info('Admin user already exists, skipping...');
        }

        // Create Regular Users from array
        $usersData = $this->getUsersData();
        foreach ($usersData as $userData) {
            $user = User::create($userData);
            $user->syncRoles([$userRole]);
        }
        $this->info('Created ' . count($usersData) . ' test users.');

        // Create Organisations from array
        $organisationsData = $this->getOrganisationsData();
        foreach ($organisationsData as $orgData) {
            Organisation::create($orgData);
        }
        $this->info('Created ' . count($organisationsData) . ' organisations.');

        // Create Journals from array
        $journalsData = $this->getJournalsData();
        foreach ($journalsData as $journalData) {
            Journal::create($journalData);
        }
        $this->info('Created ' . count($journalsData) . ' journals.');

        // Create Journal Lines from array
        $journalLinesData = $this->getJournalLinesData();
        foreach ($journalLinesData as $lineData) {
            JournalLine::create($lineData);
        }
        $this->info('Created ' . count($journalLinesData) . ' journal lines.');

        $this->info('Test data seeding completed!');
    }

    /**
     * Get users data array.
     */
    protected function getUsersData(): array
    {
        return [
            [
                'id' => 'a0544fac-074f-4a71-90e8-345153da3af2',
                'name' => 'Wilber Howell',
                'email' => 'uveum@example.net',
                'email_verified_at' => '2025-11-11 05:42:23',
                'password' => '$2y$12$/kVi468ruHRlMHLlI6nWNe08Xrfbo8fguOXhlwqyDFgrGUdXoePnq',
                'remember_token' => 'Ucpi4kPaNc',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-0b87-4bbd-96a5-bbf33151be3d',
                'name' => 'Prof. Damaris Mueller',
                'email' => 'mayer.jody@example.net',
                'email_verified_at' => '2025-11-11 05:42:23',
                'password' => '$2y$12$/kVi468ruHRlMHLlI6nWNe08Xrfbo8fguOXhlwqyDFgrGUdXoePnq',
                'remember_token' => '4BAXeE6r91',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-0cf6-4e16-9400-611e1c59ba44',
                'name' => 'Damion Kautzer',
                'email' => 'amanda71@example.org',
                'email_verified_at' => '2025-11-11 05:42:23',
                'password' => '$2y$12$/kVi468ruHRlMHLlI6nWNe08Xrfbo8fguOXhlwqyDFgrGUdXoePnq',
                'remember_token' => 'oI3Xq00YyG',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-0eac-44bb-a5ce-9d9564950507',
                'name' => 'Ms. Lucile Heaney PhD',
                'email' => 'mclaughlin.lorena@example.net',
                'email_verified_at' => '2025-11-11 05:42:23',
                'password' => '$2y$12$/kVi468ruHRlMHLlI6nWNe08Xrfbo8fguOXhlwqyDFgrGUdXoePnq',
                'remember_token' => 'AzOTiSKbEn',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-1017-42a9-91dd-f3e2dff6b879',
                'name' => 'Esperanza Weimann',
                'email' => 'fkling@example.org',
                'email_verified_at' => '2025-11-11 05:42:23',
                'password' => '$2y$12$/kVi468ruHRlMHLlI6nWNe08Xrfbo8fguOXhlwqyDFgrGUdXoePnq',
                'remember_token' => 'kRdTQPEaIK',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
        ];
    }

    /**
     * Get organisations data array.
     */
    protected function getOrganisationsData(): array
    {
        return [
            [
                'id' => 'a0544fac-1a44-42b8-8ef0-494ccc42f19c',
                'organisation_id' => 'bcb81bbf-ea01-3685-9a8c-db734f303aa0',
                'api_key' => '84e92608-a504-3f9f-a775-d82b6c0a8357',
                'name' => 'Bashirian, Berge and Renner',
                'legal_name' => 'Kuhn, Murazik and Hyatt Pty Ltd',
                'pays_tax' => true,
                'version' => 'AU',
                'organisation_type' => 'PARTNERSHIP',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => true,
                'organisation_status' => 'ARCHIVED',
                'registration_number' => null,
                'employer_identification_number' => null,
                'tax_number' => null,
                'financial_year_end_day' => 28,
                'financial_year_end_month' => 5,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'COMPANY',
                'short_code' => 'vxz',
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => null,
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-1c1c-4a6b-80dc-c1ec119e9b06',
                'organisation_id' => '291e3ac5-b215-377c-978c-263c3b6c7630',
                'api_key' => null,
                'name' => 'Erdman-Greenholt',
                'legal_name' => 'Greenholt, Jast and Schneider Pty Ltd',
                'pays_tax' => true,
                'version' => 'AU',
                'organisation_type' => 'CHARITY',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ARCHIVED',
                'registration_number' => null,
                'employer_identification_number' => null,
                'tax_number' => null,
                'financial_year_end_day' => 26,
                'financial_year_end_month' => 9,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'PARTNERSHIP',
                'short_code' => null,
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => null,
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-1d5d-479c-b11a-3cd70d9f452f',
                'organisation_id' => 'a47f6fe0-ea76-3bd4-a37b-af44c1bac2f8',
                'api_key' => null,
                'name' => 'Sauer, Blanda and Lueilwitz',
                'legal_name' => 'McKenzie-Schmitt Pty Ltd',
                'pays_tax' => false,
                'version' => 'AU',
                'organisation_type' => 'COMPANY',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ARCHIVED',
                'registration_number' => 'ABN6683949930',
                'employer_identification_number' => null,
                'tax_number' => '6113947259',
                'financial_year_end_day' => 23,
                'financial_year_end_month' => 2,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'CHARITY',
                'short_code' => 'ysh',
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => '2025-10-27 18:12:48',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-1eaa-461a-8016-9a129f906ef1',
                'organisation_id' => 'ed2f0c38-fe04-3c7c-b0a4-cb30043bae67',
                'api_key' => '2d37b29f-77cd-36e8-a83b-933fcbed85de',
                'name' => 'Torp Ltd',
                'legal_name' => 'Macejkovic Ltd Pty Ltd',
                'pays_tax' => false,
                'version' => 'AU',
                'organisation_type' => 'CHARITY',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ACTIVE',
                'registration_number' => 'ABN2519688237',
                'employer_identification_number' => null,
                'tax_number' => '8931956939',
                'financial_year_end_day' => 14,
                'financial_year_end_month' => 6,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'COMPANY',
                'short_code' => 'vrn',
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => null,
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-2010-4e0a-915e-72b8e9ff1632',
                'organisation_id' => '21188201-69be-30ef-b19e-860d4e93eb1b',
                'api_key' => '944a34de-6d82-3992-a24b-6209f112b662',
                'name' => 'Wiegand, Blick and Willms',
                'legal_name' => 'Pfeffer LLC Pty Ltd',
                'pays_tax' => true,
                'version' => 'AU',
                'organisation_type' => 'COMPANY',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ACTIVE',
                'registration_number' => 'ABN3904281150',
                'employer_identification_number' => null,
                'tax_number' => '9850671804',
                'financial_year_end_day' => 6,
                'financial_year_end_month' => 11,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'COMPANY',
                'short_code' => null,
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => null,
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-2180-4368-96c6-cc8788bebd0a',
                'organisation_id' => '0670a271-f35d-32e8-9474-b4518a117b0c',
                'api_key' => null,
                'name' => 'Daugherty, Harris and Wilderman',
                'legal_name' => 'Rolfson and Sons Pty Ltd',
                'pays_tax' => true,
                'version' => 'AU',
                'organisation_type' => 'CHARITY',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ACTIVE',
                'registration_number' => null,
                'employer_identification_number' => null,
                'tax_number' => null,
                'financial_year_end_day' => 12,
                'financial_year_end_month' => 2,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'CHARITY',
                'short_code' => 'xpt',
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => '2025-10-21 17:22:32',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-231d-4d2b-aa9f-915a12773f8f',
                'organisation_id' => 'a9c768ea-4eae-3e3c-b929-cd30534460af',
                'api_key' => 'b210ecd9-bbaa-3485-b6b9-523fac770399',
                'name' => 'Spinka-Williamson',
                'legal_name' => 'Botsford-Parker Pty Ltd',
                'pays_tax' => true,
                'version' => 'AU',
                'organisation_type' => 'PARTNERSHIP',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ACTIVE',
                'registration_number' => 'ABN3199401452',
                'employer_identification_number' => null,
                'tax_number' => '4653221988',
                'financial_year_end_day' => 8,
                'financial_year_end_month' => 9,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'CHARITY',
                'short_code' => 'hgs',
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => null,
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-2491-4915-b25b-18a4847296fb',
                'organisation_id' => 'be2f8f85-2dcc-31ad-ab12-e32fd344f671',
                'api_key' => '2ae0f9cf-3c52-3936-8f57-e2340ad23b45',
                'name' => 'Hackett and Sons',
                'legal_name' => 'Bradtke-Mohr Pty Ltd',
                'pays_tax' => false,
                'version' => 'AU',
                'organisation_type' => 'COMPANY',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ACTIVE',
                'registration_number' => 'ABN2640413396',
                'employer_identification_number' => null,
                'tax_number' => null,
                'financial_year_end_day' => 13,
                'financial_year_end_month' => 1,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'PARTNERSHIP',
                'short_code' => 'jgp',
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => '2025-11-11 04:05:29',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-25d8-4d89-ba08-78c0d2baef55',
                'organisation_id' => '674370e7-dbb8-3e77-88af-a92924b3dcce',
                'api_key' => null,
                'name' => 'Balistreri-Ziemann',
                'legal_name' => 'Hickle-Schmitt Pty Ltd',
                'pays_tax' => true,
                'version' => 'AU',
                'organisation_type' => 'CHARITY',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => false,
                'organisation_status' => 'ARCHIVED',
                'registration_number' => 'ABN8298414971',
                'employer_identification_number' => null,
                'tax_number' => null,
                'financial_year_end_day' => 5,
                'financial_year_end_month' => 6,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'COMPANY',
                'short_code' => null,
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => '2025-11-03 02:18:00',
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
            [
                'id' => 'a0544fac-272c-47b4-a447-5bd646159b24',
                'organisation_id' => 'c704d7ce-6604-3862-acac-644df40fb499',
                'api_key' => 'b75a41c5-b878-39d2-aad0-34934e6e5114',
                'name' => 'Beatty, Abbott and Daugherty',
                'legal_name' => 'Sipes, Kris and Schumm Pty Ltd',
                'pays_tax' => true,
                'version' => 'AU',
                'organisation_type' => 'SOLE_TRADER',
                'base_currency' => 'AUD',
                'country_code' => 'AU',
                'is_demo_company' => true,
                'organisation_status' => 'ACTIVE',
                'registration_number' => null,
                'employer_identification_number' => null,
                'tax_number' => null,
                'financial_year_end_day' => 25,
                'financial_year_end_month' => 8,
                'sales_tax_basis' => null,
                'sales_tax_period' => null,
                'default_sales_tax' => null,
                'default_purchases_tax' => null,
                'period_lock_date' => null,
                'end_of_year_lock_date' => null,
                'created_date_utc' => null,
                'timezone' => 'Australia/Sydney',
                'organisation_entity_type' => 'PARTNERSHIP',
                'short_code' => null,
                'class' => null,
                'edition' => null,
                'line_of_business' => null,
                'external_links' => null,
                'payment_terms' => null,
                'last_sync' => null,
                'created_at' => '2025-11-11 05:42:23',
                'updated_at' => '2025-11-11 05:42:23',
            ],
        ];
    }

    /**
     * Get journals data array.
     */
    protected function getJournalsData(): array
    {
        return ProductionDataSeeder::getJournalsData();
    }

    /**
     * Get journal lines data array.
     */
    protected function getJournalLinesData(): array
    {
        return ProductionDataSeeder::getJournalLinesData();
    }
}

