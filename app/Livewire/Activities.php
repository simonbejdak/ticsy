<?php

namespace App\Livewire;

use App\Interfaces\Activitable;
use App\Services\ActivityService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;

class Activities extends Component
{
    public Activitable|Model $model;
    public Collection $activities;
    public string $body = '';

    public function render(){
        // We do not want to render activities,
        // which have nothing recorded ({"attributes":[],"old":[]} says something changed, but we didn't record it)
        $this->activities = $this->model->activities()->where('properties', '!=', '{"attributes":[],"old":[]}')->orderByDesc('id')->get();

        return view('livewire.activities');
    }
}
