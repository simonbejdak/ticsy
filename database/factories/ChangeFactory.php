<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Change;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChangeFactory extends TicketFactory
{
    protected $model = Change::class;

    public function definition()
    {
        return [
            'category_id' => Category::factory()->create(),
            'type_id' => Ticket::TYPES['change'],
            'resolver_id' => Resolver::factory()->create(),
            'description' => fake()->sentence(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function existing(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'description' => fake()->sentence(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });
    }
}
