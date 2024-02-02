<?php

namespace Database\Factories;

use App\Enums\ResolverPanelOption;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FavoriteResolverPanelOption>
 */
class FavoriteResolverPanelOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'option' => ResolverPanelOption::INCIDENTS,
        ];
    }
}
