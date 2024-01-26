<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IncidentFactory extends Factory
{
    public function definition()
    {
        return [
            'caller_id' => User::factory(),
            'category_id' => rand(1, IncidentCategory::count()),
            'description' => fake()->realText(40),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Incident $incident) {
            if($incident->item_id === null){
                $incident->item_id = $incident->category->randomItem()->id;
            }
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
}
