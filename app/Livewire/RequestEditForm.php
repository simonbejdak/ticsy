<?php

namespace App\Livewire;

use App\Enums\OnHoldReason;
use App\Enums\Priority;
use App\Enums\Status;
use App\Enums\Tab;
use App\Helpers\Fields\Bar;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextInput;
use App\Models\Group;
use App\Models\Request;
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
                Rule::requiredIf($this->priority != $this->request->priority),
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

    public function mount(Request $request){
        $this->request = $request;
        $this->model = $request;
        $this->status = $this->request->status;
        $this->onHoldReason = $this->request->on_hold_reason;
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
        if($property === 'status' && $value == Status::OPEN){
            $this->resolver = null;
        }
        if($property === 'priority' && $value == Priority::ONE){
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
        $this->request->on_hold_reason = $this->onHoldReason ?? null;
        $this->request->priority = $this->priority;
        $this->request->group_id = $this->group;
        $this->request->resolver_id = ($this->resolver === '') ? null : $this->resolver;
        $this->request->save();

        if($this->priorityChangeReason !== ''){
            ActivityService::priorityChangeReason($this->request, $this->priorityChangeReason);
        }

        if($this->comment !== ''){
            ActivityService::comment($this->request, $this->comment);
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
                ->value($this->request->created_at->format('d.m.Y h:i:s'))
                ->disabled(),
            TextInput::make('updated')
                ->displayName('Updated at')
                ->value($this->request->updated_at->format('d.m.Y h:i:s'))
                ->disabled(),
            TextInput::make('category')
                ->value($this->request->category->name)
                ->disabled(),
            TextInput::make('item')
                ->value($this->request->item->name)
                ->disabled(),
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
                if($this->request->sla->isOpened()){
                    return Bar::make('sla')
                        ->displayName('SLA expires at')
                        ->percentage($this->request->sla->toPercentage())
                        ->value($this->request->sla->minutesTillExpires() . ' minutes')
                        ->pulse();
                } return null;
            },
            TextInput::make('priorityChangeReason')
                ->hiddenIf($this->isFieldDisabled('priorityChangeReason'))
                ->outsideGrid(),
            TextInput::make('description')
                ->value($this->request->description)
                ->disabled()
                ->outsideGrid(),
            TextInput::make('comment')
                ->displayName('Add a comment')
                ->outsideGrid(),
        );
    }

    function tabs(): array
    {
        return [Tab::ACTIVITIES, Tab::TASKS];
    }

    protected function isFieldDisabled(string $name): bool
    {
        if($this->request->isArchived()){
            return true;
        }

        if($name == 'comment'){
            return auth()->user()->cannot('addComment', $this->request);
        }

        if(auth()->user()->cannot('update', Request::class)){
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
