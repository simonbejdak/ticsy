<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'caller_id' => User::factory(),
            'category_id' => rand(1, RequestCategory::count()),
            'description' => fake()->realText(40),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Request $request) {
            if($request->category_id != null && $request->item_id != null){
                return;
            }
            if($request->item_id === null){
                $request->item_id = $request->category->randomItem()->id;
            } else {
                $request->category_id = $request->item->randomCategory()->id;
            }
        });
    }

    public function taskSequenceGradient()
    {
        // this pair is at gradient sequence
        return $this->state(function (array $attributes) {
            return [
                'category_id' => RequestCategory::SERVER,
                'item_id' => RequestItem::ACCESS,
            ];
        });
    }

    public function taskSequenceAtOnce()
    {
        // this pair is at once sequence
        return $this->state(function (array $attributes) {
            return [
                'category_id' => RequestCategory::SERVER,
                'item_id' => RequestItem::MAINTENANCE,
            ];
        });
    }

    public function withResolver()
    {
        return $this->state(function (array $attributes) {
            return [
                'resolver_id' => User::factory()->resolver(),
            ];
        });
    }

    public function statusInProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::IN_PROGRESS,
            ];
        });
    }

    public function statusOnHold()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::ON_HOLD,
            ];
        });
    }

    public function statusResolved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::RESOLVED,
            ];
        });
    }

    public function statusCancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::CANCELLED,
            ];
        });
    }

    public function withoutTaskPlan()
    {
        // this pair has no task plan
        return $this->state(function (array $attributes) {
            return [
                'category_id' => RequestCategory::COMPUTER,
                'item_id' => RequestItem::CONFIGURE,
            ];
        });
    }
}
