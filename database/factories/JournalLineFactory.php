<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JournalLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JournalLine>
 */
class JournalLineFactory extends Factory
{
    protected $model = JournalLine::class;

    public function definition(): array
    {
        $netAmount = fake()->randomFloat(2, -10000, 10000);
        $taxAmount = abs($netAmount) * 0.1;
        $grossAmount = $netAmount + $taxAmount;

        return [
            'journal_id' => \App\Models\Journal::factory(),
            'journal_line_id' => fake()->uuid(),
            'account_id' => fake()->uuid(),
            'account_code' => fake()->numerify('###'),
            'account_type' => fake()->randomElement(['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE']),
            'account_name' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'net_amount' => (string) $netAmount,
            'gross_amount' => (string) $grossAmount,
            'tax_amount' => (string) $taxAmount,
            'tax_type' => fake()->optional()->randomElement(['GST', 'NONE', 'INPUT', 'OUTPUT']),
            'tax_name' => fake()->optional()->randomElement(['GST', 'No Tax']),
            'tracking_categories' => fake()->optional()->randomElements([
                ['Name' => 'Department', 'Option' => fake()->randomElement(['Sales', 'Marketing', 'Operations'])],
                ['Name' => 'Project', 'Option' => fake()->word()],
            ], rand(0, 2)),
        ];
    }
}

