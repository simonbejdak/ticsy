<?php

namespace App\Livewire;

use App\Enums\OnHoldReason;
use App\Enums\Priority;
use App\Enums\Status;
use App\Enums\Tab;
use App\Helpers\Fields\Bar;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use App\Models\Group;
use App\Models\Task;
use App\Services\ActivityService;
use App\Traits\HasFields;
use App\Traits\HasTabs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

class TaskEditForm extends EditForm
{
    use HasFields;

    public Task $task;
    public Status $status;
    public OnHoldReason|null $onHoldReason;
    public Priority $priority;
    public string $priorityChangeReason = '';
    public $group;
    public $resolver;
    public string $comment = '';


    public function rules()
    {
        return [
            'status' => ['required', Rule::enum(Status::class)],
            'onHoldReason' => [
                Rule::requiredIf($this->status == Status::ON_HOLD),
                Rule::enum(OnHoldReason::class),
                'nullable'
            ],
            'priority' => ['required', Rule::enum(Priority::class)],
            'priorityChangeReason' => [
                Rule::requiredIf($this->priority != $this->task->priority),
                'string',
            ],
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
                Rule::requiredIf($this->status == Status::RESOLVED),
                Rule::requiredIf($this->status == Status::CANCELLED),
                Rule::requiredIf($this->status == Status::ON_HOLD),
                'nullable',
            ],
        ];
    }

    public function mount(Task $task){
        $this->task = $task;
        $this->model = $task;
        $this->setActivities();
        $this->status = $this->task->status;
        $this->onHoldReason = $this->task->on_hold_reason;
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
        if($property === 'status' && $value == Status::OPEN){
            $this->resolver = null;
        }
        if($property === 'priority' && $value == Priority::ONE){
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
        $this->task->status = $this->status;
        $this->task->on_hold_reason = $this->onHoldReason ?? null;
        $this->task->priority = $this->priority;
        $this->task->group_id = $this->group;
        $this->task->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->task->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->task, $this->priorityChangeReason);
        }

        if($this->comment !== ''){
            ActivityService::comment($this->task, $this->comment);
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
                ->value($this->task->created_at->format('d.m.Y h:i:s'))
                ->disabled(),
            TextInput::make('updated')
                ->displayName('Updated at')
                ->value($this->task->updated_at->format('d.m.Y h:i:s'))
                ->disabled(),
            function () {
                if($this->task->hasTaskable()){
                    return new Fields(
                        TextInput::make('taskable')
                            ->displayName(get_class_name($this->task->taskable))
                            ->value($this->task->taskable_id)
                            ->disabled()
                            ->anchor($this->task->taskable->editFormRoute()),
                        TextInput::make('category')
                            ->value($this->task->categoryName())
                            ->disabled(),
                        TextInput::make('item')
                            ->value($this->task->itemName())
                            ->disabled(),
                    );
                } return null;
            },
            Select::make('status')
                ->options(Status::class)
                ->disabledIf($this->isFieldDisabled('status')),
            Select::make('onHoldReason')
                ->options(OnHoldReason::class)
                ->hiddenIf($this->isFieldDisabled('onHoldReason'))
                ->blank(),
            Select::make('priority')
                ->options(Priority::class)
                ->disabledIf($this->isFieldDisabled('priority')),
            Select::make('group')
                ->options(Group::all())
                ->disabledIf($this->isFieldDisabled('group')),
            Select::make('resolver')
                ->options(Group::find($this->group) ? Group::find($this->group)->resolvers : [])
                ->disabledIf($this->isFieldDisabled('resolver'))
                ->blank(),
            function () {
                if($this->task->sla->isOpened()){
                    return Bar::make('sla')
                        ->displayName('SLA expires at')
                        ->percentage($this->task->sla->toPercentage())
                        ->value($this->task->sla->minutesTillExpires() . ' minutes')
                        ->pulse();
                } return null;
            },
            TextArea::make('priorityChangeReason')
                ->hiddenIf($this->isFieldDisabled('priorityChangeReason'))
                ->outsideGrid(),
            TextArea::make('description')
                ->value($this->task->description)
                ->disabled()
                ->outsideGrid(),
            TextArea::make('comment')
                ->displayName('Add a comment')
                ->outsideGrid(),
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
            'onHoldReason' =>
                $this->status != Status::ON_HOLD,
            'priority', 'group', 'resolver' =>
                $this->status == Status::RESOLVED,
            'priorityChangeReason' =>
                $this->priority == $this->task->priority,
            default => false,
        };
    }
}
