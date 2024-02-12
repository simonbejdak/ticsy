<?php

namespace App\Livewire;

use App\Enums\OnHoldReason;
use App\Enums\Priority;
use App\Enums\Status;
use App\Helpers\Fields\Bar;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use App\Models\Group;
use App\Models\Task;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class TaskEditForm extends EditForm
{
    public Task $task;
    public Status $status;
    public OnHoldReason|null $onHoldReason;
    public Priority $priority;
    public $group;
    public $resolver;
    public string $comment;


    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(Status::class)],
            'onHoldReason' => [
                Rule::requiredIf($this->status == Status::ON_HOLD),
                Rule::enum(OnHoldReason::class),
                'nullable'
            ],
            'priority' => ['required', Rule::enum(Priority::class)],
            'group' => ['required', 'exists:App\Models\Group,id'],
            'resolver' => [
                Rule::in(
                    Group::find($this->group) ? Group::find($this->group)->getResolverIds() : []
                ),
                Rule::requiredIf($this->status == Status::IN_PROGRESS),
                'nullable',
            ],
            'comment' => [
                'max:255',
                Rule::requiredIf($this->status == Status::RESOLVED && $this->status != $this->task->status),
                Rule::requiredIf($this->status == Status::CANCELLED && $this->status != $this->task->status),
                Rule::requiredIf($this->status == Status::ON_HOLD && $this->status != $this->task->status),
                Rule::requiredIf($this->priority != $this->task->priority),
                'nullable',
            ],
        ];
    }

    public function mount(Task $task): void
    {
        $this->task = $task;
        $this->model = $task;
        $this->setActivities();
        $this->status = $this->task->status;
        $this->onHoldReason = $this->task->on_hold_reason;
        $this->priority = $this->task->priority;
        $this->group = $this->task->group_id;
        $this->resolver = $this->task->resolver_id;
        $this->comment = '';
    }

    public function updating($property, $value): void
    {
        if($property === 'status' && $value == Status::OPEN){
            $this->resolver = null;
        }
        if($property === 'priority'){
            $this->authorize('setPriority', Task::class);
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
        $this->task->status = $this->status;
        $this->task->on_hold_reason = $this->onHoldReason ?? null;
        $this->task->priority = $this->priority;
        $this->task->group_id = $this->group;
        $this->task->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->task->save();

        if($this->comment !== ''){
            ActivityService::comment($this->task, $this->comment);
        }

        Session::flash('success', 'You have successfully updated the task');
        return redirect()->route('tasks.edit', $this->task);
    }

    function schema(): Fields
    {
        return new Fields(
            TextInput::make('number')->value($this->task->id),
            TextInput::make('caller')->value($this->task->caller->name),
            TextInput::make('created')->label('Created at')->value($this->task->created_at->format('d.m.Y h:i:s')),
            TextInput::make('updated')->label('Updated at')->value($this->task->updated_at->format('d.m.Y h:i:s')),
            function () {
                if($this->task->hasTaskable()){
                    return new Fields(
                        TextInput::make('taskable')
                            ->label(get_class_name($this->task->taskable))
                            ->value($this->task->taskable_id)
                            ->anchor($this->task->taskable->editRoute()),
                        TextInput::make('category')->value($this->task->categoryName()),
                        TextInput::make('item')->value($this->task->itemName()),
                    );
                } return null;
            },
            Select::make('status')->options(Status::class),
            Select::make('onHoldReason')->options(OnHoldReason::class)->hiddenIf($this->isFieldDisabled('onHoldReason'))->blank(),
            Select::make('priority')->options(Priority::class),
            Select::make('group')->options(Group::all()),
            Select::make('resolver')->options(Group::find($this->group) ? Group::find($this->group)->resolvers : [])->blank(),
            function () {
                if($this->task->sla->isOpened()){
                    return Bar::make('sla')
                        ->label('SLA expires at')
                        ->percentage($this->task->sla->toPercentage())
                        ->value($this->task->sla->minutesTillExpires() . ' minutes')
                        ->pulse();
                } return null;
            },
            TextArea::make('description')->value($this->task->description)->outsideGrid(),
            TextArea::make('comment')->label('Add a comment')->outsideGrid(),
        );
    }

    protected function isFieldDisabled(string $name): bool
    {
        if($this->task->isArchived()){
            return true;
        }

        if($name == 'comment'){
            return auth()->user()->cannot('addComment', $this->task);
        }

        if(auth()->user()->cannot('update', Task::class)){
            return true;
        }

        return match($name){
            'number', 'caller', 'created', 'updated', 'taskable', 'category', 'item', 'description' => true,
            'onHoldReason' => $this->status != Status::ON_HOLD,
            'priority' => $this->status != Status::ON_HOLD && !Auth::user()->hasPermissionTo('set_priority'),
            'group', 'resolver' => $this->status == Status::RESOLVED,
            default => false,
        };
    }

    function tabs(): array
    {
        return [];
    }
}
