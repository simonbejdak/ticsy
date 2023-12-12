<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Item;
use App\Models\OnHoldReason;
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
            'caller_id' => function (){
                return User::factory()->create();
            },
            'category_id' => function (){
                return rand(1, Category::count());
            },
            'type_id' => function (){
                return rand(1, Type::count());
            },
            'description' => fake()->sentence(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Ticket $ticket) {
            if($ticket->item_id === null){
                $ticket->item_id = $ticket->category->randomItem()->id;
            }
        })->afterCreating(function (Ticket $ticket) {
            //
        });
    }

    public function inProgress(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::IN_PROGRESS,
            ];
        });
    }

    public function onHold(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::ON_HOLD,
            ];
        });
    }

    public function resolved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::RESOLVED,
            ];
        });
    }

    public function cancelled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::CANCELLED,
            ];
        });
    }
}
