<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organisation>
 */
class OrganisationFactory extends Factory
{
    protected $model = Organisation::class;

    public function definition(): array
    {
        return [
            'organisation_id' => fake()->uuid(),
            'api_key' => fake()->optional()->uuid(),
            'name' => fake()->company(),
            'legal_name' => fake()->company() . ' Pty Ltd',
            'pays_tax' => fake()->boolean() ? 'YES' : 'NO',
            'version' => 'AU',
            'organisation_type' => fake()->randomElement(['COMPANY', 'CHARITY', 'PARTNERSHIP', 'SOLE_TRADER']),
            'base_currency' => 'AUD',
            'country_code' => 'AU',
            'is_demo_company' => fake()->boolean(20),
            'organisation_status' => fake()->randomElement(['ACTIVE', 'ARCHIVED']),
            'registration_number' => fake()->optional()->numerify('ABN##########'),
            'tax_number' => fake()->optional()->numerify('##########'),
            'financial_year_end_day' => fake()->numberBetween(1, 28),
            'financial_year_end_month' => fake()->numberBetween(1, 12),
            'timezone' => 'Australia/Sydney',
            'organisation_entity_type' => fake()->randomElement(['COMPANY', 'CHARITY', 'PARTNERSHIP', 'SOLE_TRADER']),
            'short_code' => fake()->optional()->lexify('???'),
            'last_sync' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}

