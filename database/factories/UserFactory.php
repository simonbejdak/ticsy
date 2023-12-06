<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (User $user) {
            // ...
        })->afterCreating(function (User $user) {
            $user->assignRole('user');
        });
    }

    public function resolver(bool $allGroups = false): Factory
    {
        return $this->afterCreating(function (User $user) use ($allGroups) {
            $user->assignRole('resolver');
            if($allGroups){
                foreach (Group::all() as $group){
                    $group->resolvers()->attach($user);
                }
            }
        });
    }

    public function manager(bool $allGroups = false): Factory
    {
        return $this->afterCreating(function (User $user) use ($allGroups) {
            $user->assignRole('manager');
        });
    }
}
