<?php

namespace App\Livewire;

use App\Helpers\Fields\Fields;
use App\Helpers\Tabs;
use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Request;
use App\Models\Status;
use App\Services\ActivityService;
use App\Traits\HasFields;
use App\Traits\HasTabs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class RequestEditForm extends Form
{
    use HasFields, HasTabs;

    public Request $request;
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
            'status' => 'required|numeric',
            'onHoldReason' => 'required_if:status,'. Status::ON_HOLD . '|nullable|numeric',
            'priority' => 'required|numeric',
            'priorityChangeReason' => $this->request->isDirty('priority') ? 'required|string' : 'present|max:0',
            'group' => 'required|numeric',
            'resolver' => 'nullable|numeric',
        ];
    }

    public function mount(Request $request){
        $this->request = $request;
        $this->status = $this->request->status_id;
        $this->onHoldReason = $this->request->on_hold_reason_id;
        $this->priority = $this->request->priority;
        $this->group = $this->request->group_id;
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
        if($property === 'status' && $this->status != Status::ON_HOLD){
            $this->onHoldReason = null;
        }

        parent::updated($property);
    }

    public function save()
    {
        $this->validate();
        $this->request->status_id = $this->status;
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
        // TODO: Implement fields() method.
    }

    function tabs(): Tabs
    {
        // TODO: Implement tabs() method.
    }
}
