<?php

namespace Database\Factories;

use App\Enums\Location;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'location' => Location::NAMESTOVO,
            'remember_token' => Str::random(10),
        ];
    }

    public function resolver(): Factory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('resolver');
        });
    }

    public function resolverAllGroups(): Factory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('resolver');
            foreach (Group::all() as $group){
                $group->resolvers()->attach($user);
            }
        });
    }

    public function manager(bool $allGroups = false): Factory
    {
        return $this->afterCreating(function (User $user) use ($allGroups) {
            $user->assignRole('manager');
        });
    }

    public function managerAllGroups(): Factory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('manager');
            foreach (Group::all() as $group){
                $group->resolvers()->attach($user);
            }
        });
    }
}
