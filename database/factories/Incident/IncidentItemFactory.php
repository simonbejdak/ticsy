<?php

namespace Database\Factories\Incident;

use App\Models\Incident\IncidentItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IncidentItem>
 */
class IncidentItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word,
        ];
    }
}
