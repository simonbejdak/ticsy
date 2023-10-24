<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Resolver;
use App\Models\Status;
use App\Models\TicketConfig;
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
                return rand(1, count(TicketConfig::CATEGORIES));
            },
            'type_id' => function (){
                return rand(1, count(TicketConfig::TYPES));
            },
            'status_id' => function (){
                return TicketConfig::DEFAULT_STATUS;
            },
            'group_id' => Group::DEFAULT,
            'description' => fake()->sentence(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
