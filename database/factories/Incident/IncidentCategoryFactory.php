<?php

namespace Database\Factories\Incident;

use App\Models\Incident\IncidentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IncidentCategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
