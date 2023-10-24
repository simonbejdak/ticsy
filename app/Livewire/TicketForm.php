<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use App\Models\User;
use Livewire\Component;

class TicketForm extends Component
{
    public Ticket $ticket;
    public $status;
    public $priority;
    public $group;
    public $resolver;

    public function updating($property, $value)
    {
        if($this->ticket->isArchived()){
            abort(403);
        }
        if($property === 'status'){
            $this->authorize('setStatus', $this->ticket);
        }
        if($property === 'priority'){
            $this->authorize('setPriority', $this->ticket);
            $this->isTicketResolved();
        }
        if($property === 'group'){
            $this->authorize('setGroup', $this->ticket);
            $this->isTicketResolved();
            $this->resolver = null;
        }
        if($property === 'resolver'){
            $this->authorize('setResolver', $this->ticket);
            $this->isTicketResolved();
            $this->isResolverFromSelectedGroup($value);
        }
    }

    public function updated($property)
    {
        if($property === 'status'){
            $this->ticket->status_id = $this->status;
        }
        if($property === 'priority'){
            $this->ticket->priority = $this->priority;
        }
        if($property === 'group'){
            $this->ticket->group_id = $this->group;
        }
        if($property === 'resolver'){
            $this->ticket->resolver_id = $this->resolver;
        }
    }

    public function mount(Ticket $ticket){
        $this->ticket = $ticket;
        $this->status = $this->ticket->status->id;
        $this->priority = $this->ticket->priority;
        $this->group = $this->ticket->group_id;
        $this->resolver = $this->ticket->resolver_id;
    }

    public function render()
    {
        return view('livewire.ticket-form');
    }

    public function save()
    {
        $this->validate([
            'status' => 'min:1|max:'. count(TicketConfiguration::STATUSES).'|numeric',
            'priority' => 'min:1|max:'. count(TicketConfiguration::PRIORITIES).'|required|numeric',
            'group' => 'min:1|max:'. count(Group::GROUPS).'|required|numeric',
            'resolver' => 'min:1|max:'. User::max('id') .'|nullable|numeric',
        ]);
        $this->ticket->status_id = $this->status;
        $this->ticket->priority = $this->priority;
        $this->ticket->group_id = $this->group;
        $this->ticket->resolver_id = $this->resolver;
        $this->ticket->save();
        $this->ticket->refresh();
        $this->render();
    }

    private function isTicketResolved(): void{
        if($this->ticket->isStatus('resolved')){
            abort(403);
        }
    }

    private function isResolverFromSelectedGroup($resolver){
        $resolver = User::find($resolver);

        if($resolver === null){
            return;
        }

        if(!$resolver->groups()->where('id', $this->group)->exists()){
            abort(403);
        }
    }
}
