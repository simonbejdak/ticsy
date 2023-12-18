<?php

namespace App\Livewire;

use App\Interfaces\Fieldable;
use App\Models\Group;
use App\Models\Incident\IncidentStatus;
use App\Models\Request\Request;
use App\Models\Request\RequestOnHoldReason;
use App\Models\Request\RequestStatus;
use App\Services\ActivityService;
use Illuminate\Support\Collection;

class RequestEditForm extends Form
{
    public Request $request;
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
            'onHoldReason' => 'required_if:status,'. IncidentStatus::ON_HOLD . '|nullable|numeric',
            'priority' => 'required|numeric',
            'priorityChangeReason' => $this->request->isDirty('priority') ? 'required|string' : 'present|max:0',
            'group' => 'required|numeric',
            'resolver' => 'nullable|numeric',
        ];
    }

    public function mount(Request $request){
        $this->request = $request;

        $this->statuses = RequestStatus::all();
        $this->status = $this->request->status_id;

        $this->onHoldReasons = RequestOnHoldReason::all();
        $this->onHoldReason = $this->request->on_hold_reason_id;

        $this->priorities = Request::PRIORITIES;
        $this->priority = $this->request->priority;

        $this->groups = Group::all();
        $this->group = $this->request->group_id;

        $this->resolvers = Group::find($this->group)->resolvers()->get();
        $this->resolver = $this->request->resolver_id;
    }

    public function render()
    {
        return view('livewire.request-edit-form');
    }

    public function updating($property, $value): void
    {
        if($property === 'priority' && $value == 1){
            $this->authorize('setPriorityOne', Request::class);
        }
    }

    public function updated($property): void
    {
        if($property === 'group'){
            $this->resolver = null;
        }
        if($property === 'status' &&  !$this->request->isStatus('on_hold')){
            $this->onHoldReason = null;
        }

        $this->syncRequest();
        parent::updated($property);
    }

    public function save(): void
    {
        $this->syncRequest();
        $this->validate();
        $this->request->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->request, $this->priorityChangeReason);
            $this->priorityChangeReason = '';
        }

        $this->dispatch('model-updated');
    }

    protected function syncRequest(): void
    {
        $this->request->status_id = $this->status;
        $this->request->on_hold_reason_id = $this->onHoldReason;
        $this->request->priority = $this->priority;
        $this->request->group_id = $this->group;
        $this->request->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->resolvers = $this->request->group ? $this->request->group->resolvers : collect([]);
    }

    protected function fieldableModel(): Fieldable{
        return $this->request;
    }
}
