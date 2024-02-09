<?php

namespace App\Livewire;

use App\Enums\FieldLabelPosition;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use App\Mail\IncidentCreated;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Traits\HasFields;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class IncidentCreateForm extends CreateForm
{
    use HasFields;

    public $category;
    public $item;
    public $description;

    public function rules()
    {
        return [
            'category' => ['required', Rule::in(IncidentCategory::MAP)],
            'item' => [
                'required',
                Rule::in(
                    IncidentCategory::find($this->category) ? IncidentCategory::find($this->category)->getItemIds() : []
                )
            ],
            'description' => ['string', 'required'],
        ];
    }

    public function mount()
    {
        $this->formTitle = 'Create an Incident';
        $this->formDescription = 'Report to us, that something does not work the way it should, we\'ll take a look';
        $this->category = null;
        $this->item = null;
        $this->description = null;
    }

    public function create()
    {
        $this->validate();

        $incident = new Incident();
        $incident->caller_id = Auth::user()->id;
        $incident->category_id = $this->category;
        $incident->item_id = $this->item;
        $incident->description = $this->description;
        $incident->save();

        Session::flash('success', 'You have successfully created an incident');
        Mail::to($incident->caller)->send(new IncidentCreated($incident));
        return redirect()->route('incidents.edit', $incident);
    }

    function fields(): Fields
    {
        return new Fields(
            Select::make('category')
                ->options(IncidentCategory::all())
                ->blank()
                ->outsideGrid()
                ->labelPosition(FieldLabelPosition::TOP)
            ,
            Select::make('item')
                ->options(IncidentCategory::find($this->category) ? IncidentCategory::find($this->category)->items()->get() : [])
                ->blank()
                ->outsideGrid()
                ->labelPosition(FieldLabelPosition::TOP),
            TextArea::make('description')
                ->label('Please describe what issue you are facing')
                ->outsideGrid()
                ->labelPosition(FieldLabelPosition::TOP),
        );
    }
}
