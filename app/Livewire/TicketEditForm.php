<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class TicketEditForm extends TicketForm
{
    public Ticket $ticket;
    public $status;
    public $onHoldReason;
    public $priority;
    public $group;
    public $resolver;

    public Collection $statuses;
    public Collection $onHoldReasons;
    public array $priorities;
    public Collection $groups;
    public Collection $resolvers;

    public function rules()
    {
        return [
            'status' => 'min:1|max:'. count(TicketConfig::STATUSES).'|numeric',
            'onHoldReason' => 'min:1|max:'. count(TicketConfig::STATUS_ON_HOLD_REASONS).'|required_if:status,'.TicketConfig::STATUSES['on_hold'].'|nullable|numeric',
            'priority' => 'min:1|max:'. count(TicketConfig::PRIORITIES).'|required|numeric',
            'group' => 'min:1|max:'. count(TicketConfig::GROUPS).'|required|numeric',
            'resolver' => 'min:1|max:'. User::max('id') .'|nullable|numeric',
        ];
    }

    public function mount(Ticket $ticket){
        $this->ticket = $ticket;
        $this->status = $this->ticket->status_id;
        $this->onHoldReason = $this->ticket->on_hold_reason_id;
        $this->priority = $this->ticket->priority;
        $this->group = $this->ticket->group_id;
        $this->resolver = $this->ticket->resolver_id;

        $this->statuses = Status::all();
        $this->onHoldReasons = OnHoldReason::all();
        $this->priorities = TicketConfig::PRIORITIES;
        $this->groups = Group::all();
        $this->resolvers = Group::find($this->group)->resolvers()->get();
    }

    public function render()
    {
        return view('livewire.ticket-edit-form');
    }

    public function updated($property): void
    {
        $this->syncTicket();

        parent::updated($property);

        if($property === 'group'){
            $this->resolver = null;
        }
        if($property === 'status' &&  !$this->ticket->isStatus('on_hold')){
            $this->onHoldReason = null;
        }
    }

    public function save()
    {
        $this->syncTicket();
        $this->validate();
        $this->ticket->save();
    }

    protected function checkOnHoldReason(){
        if($this->ticket->isStatus('on_hold') && $this->onHoldReason === null){
            abort(403);
        }
    }

    protected function syncTicket(){
        $this->ticket->status_id = $this->status;
        $this->ticket->on_hold_reason_id = $this->onHoldReason;
        $this->ticket->priority = $this->priority;
        $this->ticket->group_id = $this->group;
        $this->ticket->resolver_id = $this->resolver;
        $this->resolvers = $this->ticket->group ? $this->ticket->group->resolvers : collect([]);
    }
}
