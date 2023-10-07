<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Incident;
use App\Models\Resolver;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'category_id' => Category::factory()->create(),
            'type_id' => Type::factory()->create(),
            'resolver_id' => User::factory()->resolver()->create(),
            'description' => fake()->sentence(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function existing(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => 2,
                'category_id' => rand(1, count(Ticket::CATEGORIES)),
                'type_id' => rand(1, count(Ticket::TYPES)),
                'resolver_id' => 1,
                'description' => fake()->sentence(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });
    }
}
