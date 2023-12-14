<?php

namespace Database\Factories;

use App\Models\Request;
use App\Models\RequestCategory;
use App\Models\RequestStatus;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => rand(1, RequestCategory::count()),
            'caller_id' => User::factory(),
            'resolver_id' => User::factory()->resolver(),
            'description' => fake()->realText(40),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Request $request) {
            if($request->item_id === null){
                $request->item_id = $request->category->randomItem()->id;
            }
        })->afterCreating(function (Request $request) {
            //
        });
    }

    public function statusOnHold()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => RequestStatus::ON_HOLD,
            ];
        });
    }

    public function statusClosed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => RequestStatus::CLOSED,
            ];
        });
    }
}
