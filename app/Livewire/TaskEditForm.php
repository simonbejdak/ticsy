<?php

namespace App\Livewire;

use App\Enums\Tab;
use App\Helpers\Fields\Bar;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextInput;
use App\Models\Group;
use App\Models\Incident;
use App\Models\OnHoldReason;
use App\Models\Request;
use App\Models\Status;
use App\Models\Task;
use App\Services\ActivityService;
use App\Traits\HasFields;
use App\Traits\HasTabs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class TaskEditForm extends Form
{
    use HasFields, HasTabs;

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
        $this->model = $task;
        $this->status = $this->task->status_id;
        $this->onHoldReason = $this->task->on_hold_reason_id;
        $this->priority = $this->task->priority;
        $this->group = $this->task->group_id;
        $this->resolver = $this->task->resolver_id;
    }

    public function render()
    {
        return view('livewire.edit-form');
    }

    public function updating($property, $value): void
    {
        if($property === 'priority' && $value == 1){
            $this->authorize('setPriorityOne', Task::class);
        }
    }

    public function updated($property): void
    {
        if($property === 'group'){
            $this->resolver = null;
        }
        if($property === 'status' &&  !$this->status != Status::ON_HOLD){
            $this->onHoldReason = null;
        }

        parent::updated($property);
    }

    public function save()
    {
        $this->validate();
        $this->task->status_id = $this->status;
        $this->task->on_hold_reason_id = $this->onHoldReason;
        $this->task->priority = $this->priority;
        $this->task->group_id = $this->group;
        $this->task->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->task->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->task, $this->priorityChangeReason);
            $this->priorityChangeReason = '';
        }

        Session::flash('success', 'You have successfully updated the task');
        return redirect()->route('tasks.edit', $this->task);
    }

    function fields(): Fields
    {
        return new Fields(
            TextInput::make('number')
                ->value($this->task->id)
                ->disabled(),
            TextInput::make('caller')
                ->value($this->task->caller->name)
                ->disabled(),
            TextInput::make('created')
                ->displayName('Created at')
                ->value($this->task->created_at)
                ->disabled(),
            TextInput::make('updated')
                ->displayName('Updated at')
                ->value($this->task->updated_at)
                ->disabled(),
            TextInput::make('category')
                ->value($this->task->category->name)
                ->disabled(),
            TextInput::make('item')
                ->value($this->task->item->name)
                ->disabled(),
            Select::make('status')
                ->options(Status::all())
                ->disabledCondition($this->isFieldDisabled('status')),
            Select::make('onHoldReason')
                ->options(OnHoldReason::all())
                ->hideable()
                ->blank()
                ->disabledCondition($this->isFieldDisabled('onHoldReason')),
            Select::make('priority')
                ->options(Task::PRIORITIES)
                ->disabledCondition($this->isFieldDisabled('priority')),
            Select::make('group')
                ->options(Group::all())
                ->disabledCondition($this->isFieldDisabled('group')),
            Select::make('resolver')
                ->options(Group::find($this->group) ? Group::find($this->group)->resolvers : [])
                ->disabledCondition($this->isFieldDisabled('resolver'))
                ->blank(),
            Bar::make('sla')
                ->displayName('SLA expires at')
                ->percentage($this->task->sla->toPercentage())
                ->value($this->task->sla->minutesTillExpires() . ' minutes'),
            TextInput::make('priorityChangeReason')
                ->hideable()
                ->disabledCondition($this->isFieldDisabled('priorityChangeReason'))
                ->outsideGrid(),
            TextInput::make('description')
                ->value($this->task->description)
                ->disabled()
                ->outsideGrid(),
        );
    }

    function tabs(): array
    {
        return [Tab::ACTIVITIES];
    }
}
