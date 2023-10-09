<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'ticket_id' => function (){
                return Ticket::factory()->create();
            },
            'user_id' => function (){
                return User::factory()->create();
            },
            'body' => fake()->sentence(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
