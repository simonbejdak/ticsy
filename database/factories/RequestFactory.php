<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Request;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RequestFactory extends TicketFactory
{
    protected $model = Request::class;

    public function definition()
    {
        return [
            'category_id' => Category::factory()->create(),
            'type_id' => Ticket::TYPES['request'],
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
