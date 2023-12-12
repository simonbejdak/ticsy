<?php

namespace Database\Factories;

use App\Models\RequestCategory;
use App\Models\RequestStatus;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => rand(1, RequestCategory::count()),
            'status_id' => rand(1, RequestStatus::count()),
            'caller_id' => User::factory(),
            'resolver_id' => User::factory()->resolver(),
            'description' => fake()->realText(40),
        ];
    }
}
