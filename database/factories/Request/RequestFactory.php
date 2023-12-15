<?php

namespace Database\Factories\Request;

use App\Models\Request\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request\Request>
 */
class RequestFactory extends Factory
{
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
        });
    }

    public function statusInProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => RequestStatus::IN_PROGRESS,
            ];
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

    public function statusCancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => RequestStatus::CANCELLED,
            ];
        });
    }
}
