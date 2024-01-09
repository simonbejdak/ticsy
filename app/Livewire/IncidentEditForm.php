<?php

namespace App\Livewire;

use App\Enums\Tab;
use App\Helpers\Fields\Bar;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextInput;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\OnHoldReason;
use App\Enums\Status;
use App\Models\User;
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
    public $status;
    public $onHoldReason;
    public $priority;
    public string $priorityChangeReason = '';
    public $group;
    public $resolver;

    public function rules()
    {
        return [
            'status' => ['required', Rule::enum(Status::class)],
            'onHoldReason' => [
                Rule::requiredIf($this->status == Status::ON_HOLD),
                'nullable',
                Rule::in(OnHoldReason::MAP)
            ],
            'priority' => ['required', Rule::in(Incident::PRIORITIES)],
            'priorityChangeReason' => [
                Rule::requiredIf($this->priority != $this->incident->priority),
                'string',
            ],
            'group' => ['required', Rule::in(Group::MAP)],
            'resolver' => [
                'nullable',
                Rule::in(
                    Group::find($this->group) ? Group::find($this->group)->getResolverIds() : []
                )
            ],
        ];
    }

    public function mount(Incident $incident){
        $this->incident = $incident;
        $this->model = $incident;
        $this->status = $this->incident->status;
        $this->onHoldReason = $this->incident->on_hold_reason_id;
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
        if($property === 'priority' && $value == 1){
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
        $this->incident->on_hold_reason_id = $this->onHoldReason;
        $this->incident->priority = $this->priority;
        $this->incident->group_id = $this->group;
        $this->incident->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->incident->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->incident, $this->priorityChangeReason);
            $this->priorityChangeReason = '';
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
                ->options(OnHoldReason::all())
                ->hideable()
                ->blank()
                ->disabledCondition($this->isFieldDisabled('onHoldReason')),
            Select::make('priority')
                ->options(Incident::PRIORITIES)
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
        );
    }

    function tabs(): array
    {
        return [Tab::ACTIVITIES];
    }

    protected function isFieldDisabled(string $name): bool
    {
        if($this->incident->isArchived() || auth()->user()->cannot('update', Incident::class)){
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
