<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\XeroLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\XeroLog>
 */
class XeroLogFactory extends Factory
{
    protected $model = XeroLog::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['success', 'failed', 'warning']);
        $type = fake()->randomElement(['sync', 'error', 'info']);

        return [
            'organisation_id' => Organisation::factory(),
            'type' => $type,
            'status' => $status,
            'message' => match($status) {
                'success' => fake()->randomElement([
                    'Successfully synced journals',
                    'Organisation data updated',
                    'Sync completed successfully',
                ]),
                'failed' => fake()->randomElement([
                    'Failed to connect to Xero API',
                    'Authentication error',
                    'Rate limit exceeded',
                ]),
                'warning' => fake()->randomElement([
                    'Some journals were skipped',
                    'Partial sync completed',
                ]),
            },
            'error_details' => $status === 'failed' ? fake()->sentence() : null,
            'metadata' => [
                'records_processed' => fake()->numberBetween(0, 1000),
                'duration_seconds' => fake()->randomFloat(2, 0.5, 300),
            ],
            'sync_time' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}

