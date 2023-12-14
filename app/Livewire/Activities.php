<?php

namespace App\Livewire;

use App\Helpers\Activitable;
use App\Models\Comment;
use App\Models\Ticket;
use App\Services\ActivityService;
use App\Services\TicketService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Activities extends Component
{
    public Activitable|Model $model;
    public Collection $activities;
    public string $body = '';
    protected $listeners = ['ticket-updated'];

    public function mount(Activitable|Model $model): void
    {
        $this->model = $model;
    }

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

    #[On('request-updated')]
    public function requestUpdated(): void
    {
        $this->render();
    }
}
