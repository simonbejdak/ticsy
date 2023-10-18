<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{
    public Ticket $ticket;
    public $status;
    public $priority;
    public $group;
    public $resolver;

    public function mount(){
        $this->status = $this->ticket->status->id;
        $this->priority = $this->ticket->priority;
        $this->group = $this->ticket->group_id;
        $this->resolver = $this->ticket->resolver_id;
    }

    public function render()
    {
        return view('livewire.ticket-form');
    }

    public function update()
    {
        if($this->ticket->isArchived()){
            abort(403);
        }

        $this->updateStatus();
        $this->updatePriority();
        $this->updateGroup();
        $this->updateResolver();

        $this->ticket->save();
        $this->ticket->refresh();
        $this->render();
    }

    private function updateStatus(){
        $rules = 'min:1|max:'. count(TicketConfiguration::STATUSES).'|numeric';

        if($this->status !== $this->ticket->status_id){
            $this->authorize('setStatus', $this->ticket);
            $this->validate(['status' => $rules]);
            if(!empty($this->status)){
                $this->ticket->status_id = $this->status;
            }
        }
    }

    private function updatePriority(){
        $rules = 'min:1|max:'. count(TicketConfiguration::PRIORITIES).'|required|numeric';

        if($this->priority !== $this->ticket->priority){
            $this->authorize('setPriority', $this->ticket);
            $this->validate(['priority' => $rules]);
            $this->isTicketResolved();
            $this->ticket->priority = $this->priority;
        }
    }

    private function updateGroup(){
        $rules = 'min:1|max:'. count(Group::GROUPS).'|required|numeric';

        if($this->group !== $this->ticket->group_id){
            $this->authorize('setGroup', $this->ticket);
            $this->validate(['group' => $rules]);
            $this->isTicketResolved();
            $this->ticket->group_id = $this->group;
        }
    }

    private function updateResolver(){
        $rules = 'min:1|max:'. count(User::role('resolver')->get()).'|numeric';

        if($this->resolver !== $this->ticket->resolver_id){
            $this->authorize('setResolver', $this->ticket);
            $this->validate(['resolver' => $rules]);
            $this->isTicketResolved();
            if(!empty($this->resolver)){
                $this->ticket->resolver_id = $this->resolver;
            }
        }
    }

    private function isTicketResolved(): void{
        if($this->ticket->isStatus('resolved')){
            abort(403);
        }
    }
}
