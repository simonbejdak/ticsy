<?php

namespace Database\Factories;

use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'description' => fake()->realText(40),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
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
                'status_id' => Status::IN_PROGRESS,
            ];
        });
    }

    public function statusOnHold()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::ON_HOLD,
            ];
        });
    }

    public function statusResolved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::RESOLVED,
            ];
        });
    }

    public function statusCancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::CANCELLED,
            ];
        });
    }
}
