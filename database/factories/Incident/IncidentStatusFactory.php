<?php

namespace Database\Factories\Incident;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incident\IncidentStatus>
 */
class IncidentStatusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
