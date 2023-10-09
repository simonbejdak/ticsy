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
            'user_id' => function (){
                return User::factory()->create();
            },
            'category_id' => function (){
                return Category::factory()->create();
            },
            'type_id' => function (){
                return Type::factory()->create();
            },
            'resolver_id' => function (){
                return User::factory()->resolver()->create();
            },
            'description' => fake()->sentence(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
