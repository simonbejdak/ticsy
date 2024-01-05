<?php

namespace App\Livewire;

use App\Helpers\TabList;
use App\Interfaces\Fieldable;
use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Request;
use App\Models\Status;
use App\Models\Task;
use App\Services\ActivityService;
use App\Traits\HasTabs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class TaskEditForm extends Form
{
    use HasTabs;

    public Task $task;
    public array $tabs = ['activities'];
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
            'priorityChangeReason' => $this->task->isDirty('priority') ? 'required|string' : 'present|max:0',
            'group' => 'required|numeric',
            'resolver' => 'nullable|numeric',
        ];
    }

    public function mount(Task $task){
        $this->task = $task;

        $this->statuses = Status::all();
        $this->status = $this->task->status_id;

        $this->onHoldReasons = OnHoldReason::all();
        $this->onHoldReason = $this->task->on_hold_reason_id;

        $this->priorities = Request::PRIORITIES;
        $this->priority = $this->task->priority;

        $this->groups = Group::all();
        $this->group = $this->task->group_id;

        $this->resolvers = Group::find($this->group)->resolvers()->get();
        $this->resolver = $this->task->resolver_id;
    }

    public function render()
    {
        return view('livewire.task-edit-form');
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
        if($property === 'status' &&  !$this->task->isStatus('on_hold')){
            $this->onHoldReason = null;
        }

        $this->syncRequest();
        parent::updated($property);
    }

    public function save()
    {
        $this->syncRequest();
        $this->validate();
        $this->task->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->task, $this->priorityChangeReason);
            $this->priorityChangeReason = '';
        }

        Session::flash('success', 'You have successfully updated the task');
        return redirect()->route('tasks.edit', $this->task);
    }

    protected function syncRequest(): void
    {
        $this->task->status_id = $this->status;
        $this->task->on_hold_reason_id = $this->onHoldReason;
        $this->task->priority = $this->priority;
        $this->task->group_id = $this->group;
        $this->task->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->resolvers = $this->task->group ? $this->task->group->resolvers : collect([]);
    }

    protected function fieldableModel(): Fieldable{
        return $this->task;
    }
}
