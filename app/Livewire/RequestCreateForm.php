<?php

namespace App\Livewire;

use App\Models\Incident\IncidentCategory;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RequestCreateForm extends Form
{
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
        $this->categories = RequestCategory::all();
        $this->items = collect([]);
    }

    public function updated($property): void
    {
        $this->validateOnly('category');
        if($this->category !== null){
            $this->items = RequestCategory::findOrFail($this->category)->items()->get();
        }
    }

    public function render()
    {
        return view('livewire.request-create-form');
    }

    public function create()
    {
        $this->validate();

        $request = new Request();
        $request->caller_id = Auth::user()->id;
        $request->category_id = $this->category;
        $request->item_id = $this->item;
        $request->description = $this->description;
        $request->save();

        Session::flash('success', 'You have successfully created a request');
        return redirect()->route('requests.edit', $request);
    }
}
