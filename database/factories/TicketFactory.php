<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Type;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            //
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
