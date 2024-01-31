<?php

namespace Database\Factories;

use App\Enums\OnHoldReason;
use App\Enums\Status;
use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'caller_id' => User::factory(),
            'description' => fake()->realText(40),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function withCaller(User $caller)
    {
        $taskable = Request::factory(['caller_id' => $caller])->create();

        return $this->state(function () use ($taskable){
            return [
                'taskable_type' => get_class($taskable),
                'taskable_id' => $taskable->id,
            ];
        });
    }

    public function withResolver()
    {
        return $this->state(function () {
            return [
                'resolver_id' => User::factory()->resolver(),
            ];
        });
    }

    public function statusInProgress()
    {
        return $this->state(function () {
            return [
                'status' => Status::IN_PROGRESS,
            ];
        });
    }

    public function statusOnHold()
    {
        return $this->state(function () {
            return [
                'status' => Status::ON_HOLD,
                'on_hold_reason' => OnHoldReason::CALLER_RESPONSE,
            ];
        });
    }

    public function statusResolved()
    {
        return $this->state(function () {
            return [
                'status' => Status::RESOLVED,
            ];
        });
    }

    public function statusCancelled()
    {
        return $this->state(function () {
            return [
                'status' => Status::CANCELLED,
            ];
        });
    }

    public function started()
    {
        return $this->state(function () {
            return [
                'started_at' => Carbon::now(),
            ];
        });
    }
}
