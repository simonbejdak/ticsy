<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigurationItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'serial_number' => strtoupper(fake()->bothify('???#####')),
            'group_id' => Group::factory(),
        ];
    }
}
