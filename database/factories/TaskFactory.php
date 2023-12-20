<?php

namespace Database\Factories;

use App\Models\Incident\IncidentCategory;
use App\Models\Request\Request;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'caller_id' => User::factory(),
            'request_id' => Request::factory(),
            'description' => fake()->realText(40),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
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
