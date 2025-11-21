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
            'name' => fake()->company(),
            'database_host' => fake()->optional()->ipv4(),
            'database_name' => fake()->optional()->lexify('db_????????'),
            'database_username' => fake()->optional()->userName(),
            'database_password' => fake()->optional()->password(),
            'database_port' => fake()->randomElement([3306, 5432, 1433]),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }
}

