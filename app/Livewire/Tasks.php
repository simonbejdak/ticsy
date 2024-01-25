<?php

namespace App\Livewire;

use App\Interfaces\Taskable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
