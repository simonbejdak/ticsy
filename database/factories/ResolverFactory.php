<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Resolver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ResolverFactory extends Factory
{
    protected $model = Resolver::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'group_id' => Group::factory()->create(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
