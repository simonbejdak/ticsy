<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketActivities extends Component
{
    public Ticket $ticket;
    public Collection $activities;
    public string $body = '';
    protected $listeners = ['ticket-updated'];

    public function render()
    {
        $this->activities = $this->ticket->activities()->orderByDesc('id')->get();

        return view('livewire.ticket-activities');
    }

    public function addComment(){
        $this->authorize('addComment', $this->ticket);

        $this->validate([
            'body' => 'min:'. Comment::MIN_BODY_CHARS .'|max:'. Comment::MAX_BODY_CHARS .'|required',
        ]);

        $this->ticket->addComment($this->body);

        $this->reset('body');
    }

    #[On('ticket-updated')]
    public function ticketUpdated()
    {
        $this->render();
    }
}
