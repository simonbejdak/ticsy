<?php

namespace App\Livewire;

use App\Enums\FieldLabelPosition;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Mail\RequestCreated;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class RequestCreateForm extends CreateForm
{
    public $category;
    public $item;
    public $description;

    public function rules(): array
    {
        return [
            'category' => ['required', Rule::in(RequestCategory::MAP)],
            'item' => [
                'required',
                Rule::in(
                    RequestCategory::find($this->category) ? RequestCategory::find($this->category)->getItemIds() : []
                )
            ],
            'description' => ['string', 'required'],
        ];
    }

    public function mount(): void
    {
        $this->formTitle = 'Create a Request';
        $this->formDescription = 'Everything works as expected, you just need us to do something for you';
        $this->category = null;
        $this->item = null;
        $this->description = null;
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
        Mail::to($request->caller)->send(new RequestCreated($request));
        return redirect()->route('requests.edit', $request);
    }

    function schema(): Fields
    {
        return new Fields(
            Select::make('category')->options(RequestCategory::all())->blank(),
            Select::make('item')
                ->options(RequestCategory::find($this->category) ? RequestCategory::find($this->category)->items()->get() : [])
                ->blank(),
            TextArea::make('description')->label('Please describe what do you need')
        );
    }

    protected function isFieldDisabled(string $name): bool
    {
        return false;
    }

    function tabs(): array
    {
        return [];
    }
}
