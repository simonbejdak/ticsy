<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{
    public Ticket $ticket;
    public $priority;
    public $priorities;
    public $status;
    public $statuses;
    public $resolver;
    public $resolvers;

    public function mount(){
        $this->priority = $this->ticket->priority;
        $this->status = $this->ticket->status_id;
        $this->resolver = $this->ticket->resolver_id;

        $this->priorities = TicketConfiguration::PRIORITIES;
        $this->statuses = Status::all();
        $this->resolvers = User::role('resolver')->get();
    }

    public function render()
    {
        return view('livewire.ticket-form');
    }

    public function update()
    {
        if($this->priority !== $this->ticket->priority){
            $this->authorize('setPriority', $this->ticket);
            $this->validate([
                'priority' => 'min:1|max:'. count(TicketConfiguration::PRIORITIES).'|required|numeric']
            );
            $this->ticket->priority = $this->priority;
        }
        if($this->status !== $this->ticket->status_id){
            $this->authorize('setStatus', $this->ticket);
            $this->validate([
                'status' => 'min:1|max:'. count(TicketConfiguration::STATUSES).'|required|numeric']
            );
            $this->ticket->status_id = $this->status;
        }
        if($this->resolver !== $this->ticket->resolver_id){
            $this->authorize('setResolver', $this->ticket);
            if(!empty($this->resolver)){
                $this->ticket->resolver_id = $this->resolver;
            }
        }

        $this->ticket->save();
        $this->render();
    }
}
