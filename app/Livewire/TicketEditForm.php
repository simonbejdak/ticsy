<?php

namespace App\Livewire;

use App\Helpers\Fieldable;
use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Request;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class TicketEditForm extends Form
{
    public Ticket $ticket;
    public Collection $activities;
    public $status;
    public $onHoldReason;
    public $priority;
    public string $priorityChangeReason = '';
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
            'status' => 'required|numeric',
            'onHoldReason' => 'required_if:status,'. Status::ON_HOLD . '|nullable|numeric',
            'priority' => 'required|numeric',
            'priorityChangeReason' => $this->ticket->isDirty('priority') ? 'required|string' : 'present|max:0',
            'group' => 'required|numeric',
            'resolver' => 'nullable|numeric',
        ];
    }

    public function mount(Ticket $ticket){
        $this->ticket = $ticket;

        $this->statuses = Status::all();
        $this->status = $this->ticket->status_id;

        $this->onHoldReasons = OnHoldReason::all();
        $this->onHoldReason = $this->ticket->on_hold_reason_id;

        $this->priorities = Ticket::PRIORITIES;
        $this->priority = $this->ticket->priority;

        $this->groups = Group::all();
        $this->group = $this->ticket->group_id;

        $this->resolvers = Group::find($this->group)->resolvers()->get();
        $this->resolver = $this->ticket->resolver_id;
    }

    public function render()
    {
        return view('livewire.ticket-edit-form');
    }

    public function updating($property, $value): void
    {
        if($property === 'priority' && $value == 1){
            $this->authorize('setPriorityOne', Ticket::class);
        }
    }

    public function updated($property): void
    {
        if($property === 'group'){
            $this->resolver = null;
        }
        if($property === 'status' &&  !$this->ticket->isStatus('on_hold')){
            $this->onHoldReason = null;
        }

        $this->syncTicket();
        parent::updated($property);
    }

    public function save(): void
    {
        $this->syncTicket();
        $this->validate();
        $this->ticket->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->ticket, $this->priorityChangeReason);
            $this->priorityChangeReason = '';
        }

        $this->dispatch('ticket-updated');
    }

    protected function syncTicket(): void
    {
        $this->ticket->status_id = $this->status;
        $this->ticket->on_hold_reason_id = $this->onHoldReason;
        $this->ticket->priority = $this->priority;
        $this->ticket->group_id = $this->group;
        $this->ticket->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->resolvers = $this->ticket->group ? $this->ticket->group->resolvers : collect([]);
    }

    protected function fieldableModel(): Fieldable{
        return $this->ticket;
    }
}
