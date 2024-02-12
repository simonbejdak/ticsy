<?php

namespace Database\Factories;

use App\Enums\ConfigurationItemStatus;
use App\Enums\ConfigurationItemType;
use App\Enums\Location;
use App\Enums\OperatingSystem;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigurationItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'serial_number' => strtoupper(fake()->bothify('???#####')),
            'location' => Location::NAMESTOVO,
            'operating_system' => OperatingSystem::WINDOWS_10,
            'status' => ConfigurationItemStatus::INSTALLED,
            'type' => ConfigurationItemType::PRIMARY,
            'user_id' => User::factory(),
        ];
    }
}
