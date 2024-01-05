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
    protected $listeners = ['ticket-updated'];

    public function render(){
        $this->activities = $this->model->activities()->orderByDesc('id')->get();

        return view('livewire.activities');
    }

    public function addComment(): void
    {
        $this->authorize('addComment', $this->model);

        $this->validate([
            'body' => 'max:255|required',
        ]);

        ActivityService::comment($this->model, $this->body);

        $this->reset('body');
    }

    #[On('model-updated')]
    public function modelUpdated(): void
    {
        $this->render();
    }
}
