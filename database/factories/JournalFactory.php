<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Journal>
 */
class JournalFactory extends Factory
{
    protected $model = Journal::class;

    public function definition(): array
    {
        return [
            'organisation_id' => \App\Models\Organisation::factory(),
            'journal_id' => fake()->uuid(),
            'journal_date' => fake()->date('Y-m-d'),
            'journal_number' => fake()->numberBetween(1, 10000),
            'created_date_utc' => fake()->dateTime()->format('Y-m-d\TH:i:s'),
            'reference' => fake()->optional()->sentence(),
            'source_id' => fake()->optional()->uuid(),
            'source_type' => fake()->optional()->randomElement(['ACCREC', 'ACCPAY', 'MANUAL']),
        ];
    }
}

