<?php

namespace App\Livewire;

use App\Interfaces\Taskable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Tasks extends Component
{
    public Taskable|Model $model;
    public Collection $tasks;

    public function render(){
        $this->tasks = $this->model->tasks()->orderByDesc('id')->get();

        return view('livewire.tasks');
    }

}
