<?php

namespace App\Livewire;

use App\Interfaces\Fieldable;
use App\Models\Group;
use App\Models\Incident;
use App\Models\OnHoldReason;
use App\Models\Status;
use App\Models\Ticket;
use App\Services\ActivityService;
use Illuminate\Support\Collection;

class IncidentEditForm extends Form
{
    public Incident $incident;
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
            'priorityChangeReason' => $this->incident->isDirty('priority') ? 'required|string' : 'present|max:0',
            'group' => 'required|numeric',
            'resolver' => 'nullable|numeric',
        ];
    }

    public function mount(Incident $incident){
        $this->incident = $incident;

        $this->statuses = Status::all();
        $this->status = $this->incident->status_id;

        $this->onHoldReasons = OnHoldReason::all();
        $this->onHoldReason = $this->incident->on_hold_reason_id;

        $this->priorities = Incident::PRIORITIES;
        $this->priority = $this->incident->priority;

        $this->groups = Group::all();
        $this->group = $this->incident->group_id;

        $this->resolvers = Group::find($this->group)->resolvers()->get();
        $this->resolver = $this->incident->resolver_id;
    }

    public function render()
    {
        return view('livewire.incident-edit-form');
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
        if($property === 'status' &&  !$this->incident->isStatus('on_hold')){
            $this->onHoldReason = null;
        }

        $this->syncTicket();
        parent::updated($property);
    }

    public function save(): void
    {
        $this->syncTicket();
        $this->validate();
        $this->incident->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->incident, $this->priorityChangeReason);
            $this->priorityChangeReason = '';
        }

        $this->dispatch('model-updated');
    }

    protected function syncTicket(): void
    {
        $this->incident->status_id = $this->status;
        $this->incident->on_hold_reason_id = $this->onHoldReason;
        $this->incident->priority = $this->priority;
        $this->incident->group_id = $this->group;
        $this->incident->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->resolvers = $this->incident->group ? $this->incident->group->resolvers : collect([]);
    }

    protected function fieldableModel(): Fieldable{
        return $this->incident;
    }
}
