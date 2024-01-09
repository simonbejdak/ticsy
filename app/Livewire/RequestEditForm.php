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
use App\Services\ActivityService;
use App\Traits\HasFields;
use App\Traits\HasTabs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

class RequestEditForm extends Form
{
    use HasFields, HasTabs;

    public Request $request;
    #[Locked]
    public array $tabs;
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
            'status' => ['required', Rule::in(Status::MAP)],
            'onHoldReason' => [
                'required_if:status,' . Status::ON_HOLD,
                'nullable',
                Rule::in(OnHoldReason::MAP)
            ],
            'priority' => ['required', Rule::in(Request::PRIORITIES)],
            'priorityChangeReason' => [
                Rule::requiredIf($this->priority != $this->request->priority),
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

    public function mount(Request $request){
        $this->request = $request;
        $this->model = $request;
        $this->status = $this->request->status;
        $this->onHoldReason = $this->request->on_hold_reason_id;
        $this->priority = $this->request->priority;
        $this->group = $this->request->group_id;
        $this->resolver = $this->request->resolver_id;
    }

    public function render()
    {
        return view('livewire.edit-form');
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
        if($property === 'status' && $this->status != Status::ON_HOLD){
            $this->onHoldReason = null;
        }

        parent::updated($property);
    }

    public function save()
    {
        $this->validate();
        $this->request->status = $this->status;
        $this->request->on_hold_reason_id = $this->onHoldReason;
        $this->request->priority = $this->priority;
        $this->request->group_id = $this->group;
        $this->request->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->request->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->request, $this->priorityChangeReason);
            $this->priorityChangeReason = '';
        }

        Session::flash('success', 'You have successfully updated the request');
        return redirect()->route('requests.edit', $this->request);
    }

    function fields(): Fields
    {
        return new Fields(
            TextInput::make('number')
                ->value($this->request->id)
                ->disabled(),
            TextInput::make('caller')
                ->value($this->request->caller->name)
                ->disabled(),
            TextInput::make('created')
                ->displayName('Created at')
                ->value($this->request->created_at)
                ->disabled(),
            TextInput::make('updated')
                ->displayName('Updated at')
                ->value($this->request->updated_at)
                ->disabled(),
            TextInput::make('category')
                ->value($this->request->category->name)
                ->disabled(),
            TextInput::make('item')
                ->value($this->request->item->name)
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
                ->options(Request::PRIORITIES)
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
                ->percentage($this->request->sla->toPercentage())
                ->value($this->request->sla->minutesTillExpires() . ' minutes'),
            TextInput::make('priorityChangeReason')
                ->hideable()
                ->disabledCondition($this->isFieldDisabled('priorityChangeReason'))
                ->outsideGrid(),
            TextInput::make('description')
                ->value($this->request->description)
                ->disabled()
                ->outsideGrid(),
        );
    }

    function tabs(): array
    {
        return [Tab::ACTIVITIES, Tab::TASKS];
    }

    protected function isFieldDisabled(string $name): bool
    {
        if($this->request->isArchived() || auth()->user()->cannot('update', Incident::class)){
            return true;
        }

        return match($name){
            'onHoldReason' =>
                $this->status != Status::ON_HOLD,
            'priority', 'group', 'resolver' =>
                $this->status == Status::RESOLVED,
            'priorityChangeReason' =>
                $this->priority == $this->request->priority,
            default => false,
        };
    }
}
