<?php

namespace App\Livewire;

use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextInput;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Traits\HasFields;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IncidentCreateForm extends Form
{
    use HasFields;

    public string $typeName;
    public $category;
    public $item;
    public $description;

    public function rules()
    {
        return [
            'category' => 'numeric|required',
            'item' => 'numeric|required',
            'description' => 'string|required',
        ];
    }

    public function mount()
    {
        $this->category = null;
        $this->item = null;
        $this->description = null;
    }

    public function updated($property): void
    {
        $this->validateOnly('category');
    }

    public function render()
    {
        return view('livewire.incident-create-form');
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
        return redirect()->route('incidents.edit', $incident);
    }

    function fields(): Fields
    {
        return new Fields(
            Select::make('category')
                ->options(IncidentCategory::all())
                ->blank(),
            Select::make('item')
                ->options($this->category ? IncidentCategory::findOrFail($this->category)->items()->get() : [])
                ->blank(),
            TextInput::make('description'),
        );
    }
}
