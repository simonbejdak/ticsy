<?php

namespace App\Livewire;

use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\TicketConfig;
use App\Models\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IncidentCreateForm extends Form
{
    public string $typeName;
    public $category;
    public $item;
    public $description;
    public Collection $categories;
    public Collection $items;

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
        $this->categories = IncidentCategory::all();
        $this->items = collect([]);
    }

    public function updated($property): void
    {
        $this->validateOnly('category');
        $this->items = $this->category ? IncidentCategory::findOrFail($this->category)->items()->get() : collect([]);
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
}
