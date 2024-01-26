<?php

namespace App\Livewire;

use App\Interfaces\Taskable;
use Livewire\Component;

class Tasks extends Component
{
    protected Taskable $model;

    public function mount(Taskable $model){
        $this->model = $model;
    }

    public function render(){
        return view('livewire.tasks', ['taskable' => $this->model]);
    }
}
