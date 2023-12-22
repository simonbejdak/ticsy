<?php

namespace Database\Factories\Request;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request\RequestItem>
 */
class RequestItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word,
        ];
    }
}
