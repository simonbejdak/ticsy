<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\Tab;
use App\Helpers\Fields\Bar;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextInput;
use App\Models\Group;
use App\Models\Incident;
use App\Enums\OnHoldReason;
use App\Enums\Status;
use App\Services\ActivityService;
use App\Traits\HasFields;
use App\Traits\HasTabs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class IncidentEditForm extends Form
{
    use HasFields, HasTabs;

    public Incident $incident;
    public Collection $activities;
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
                'nullable',
            ],
            'priority' => ['required', Rule::enum(Priority::class)],
            'priorityChangeReason' => [
                Rule::requiredIf($this->priority != $this->incident->priority),
                'string',
            ],
            'group' => 'required|exists:App\Models\Group,id',
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

    public function mount(Incident $incident){
        $this->incident = $incident;
        $this->model = $incident;
        $this->status = $this->incident->status;
        $this->onHoldReason = $this->incident->on_hold_reason;
        $this->priority = $this->incident->priority;
        $this->group = $this->incident->group_id;
        $this->resolver = $this->incident->resolver_id;
    }

    public function render()
    {
        return view('livewire.edit-form');
    }

    public function updating($property, $value): void
    {
        if($property === 'priority' && $value == Priority::ONE){
            $this->authorize('setPriorityOne', Incident::class);
        }
    }

    public function updated($property): void
    {
        if($property === 'group'){
            $this->resolver = null;
        }
        if($property === 'status' && $this->status != Status::ON_HOLD){
            $this->onHoldReason = null;
        }

        parent::updated($property);
    }

    public function save()
    {
        $this->validate();
        $this->incident->status = $this->status;
        $this->incident->on_hold_reason = $this->onHoldReason ?? null;
        $this->incident->priority = $this->priority;
        $this->incident->group_id = $this->group;
        $this->incident->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->incident->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->incident, $this->priorityChangeReason);
        }

        if($this->comment !== ''){
            ActivityService::comment($this->incident, $this->comment);
        }

        Session::flash('success', 'You have successfully updated the incident');
        return redirect()->route('incidents.edit', $this->incident);
    }

    function fields(): Fields
    {
        return new Fields(
            TextInput::make('number')
                ->value($this->incident->id)
                ->disabled(),
            TextInput::make('caller')
                ->value($this->incident->caller->name)
                ->disabled(),
            TextInput::make('created')
                ->displayName('Created at')
                ->value($this->incident->created_at)
                ->disabled(),
            TextInput::make('updated')
                ->displayName('Updated at')
                ->value($this->incident->updated_at)
                ->disabled(),
            TextInput::make('category')
                ->value($this->incident->category->name)
                ->disabled(),
            TextInput::make('item')
                ->value($this->incident->item->name)
                ->disabled(),
            Select::make('status')
                ->options(Status::class)
                ->disabledCondition($this->isFieldDisabled('status')),
            Select::make('onHoldReason')
                ->options(OnHoldReason::class)
                ->hideable()
                ->blank()
                ->disabledCondition($this->isFieldDisabled('onHoldReason')),
            Select::make('priority')
                ->options(Priority::class)
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
                ->percentage($this->incident->sla->toPercentage())
                ->value($this->incident->sla->minutesTillExpires() . ' minutes'),
            TextInput::make('priorityChangeReason')
                ->hideable()
                ->disabledCondition($this->isFieldDisabled('priorityChangeReason'))
                ->outsideGrid(),
            TextInput::make('description')
                ->value($this->incident->description)
                ->disabled()
                ->outsideGrid(),
            TextInput::make('comment')
                ->withoutLabel()
                ->placeholder('Add a comment')
                ->outsideGrid(),
        );
    }

    function tabs(): array
    {
        return [Tab::ACTIVITIES];
    }

    protected function isFieldDisabled(string $name): bool
    {
        if($this->incident->isArchived()){
            return true;
        }

        if($name == 'comment'){
            return auth()->user()->cannot('addComment', $this->incident);
        }

        if(auth()->user()->cannot('update', Incident::class)){
            return true;
        }

        return match($name){
            'onHoldReason' =>
                $this->status != Status::ON_HOLD,
            'priority', 'group', 'resolver' =>
                $this->status == Status::RESOLVED,
            'priorityChangeReason' =>
                $this->priority == $this->incident->priority,
            default => false,
        };
    }
}
