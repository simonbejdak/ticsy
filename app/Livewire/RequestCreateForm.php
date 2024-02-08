<?php

namespace App\Livewire;

use App\Enums\FieldLabelPosition;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use App\Mail\RequestCreated;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Traits\HasFields;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class RequestCreateForm extends CreateForm
{
    use HasFields;

    public $category;
    public $item;
    public $description;

    public function rules()
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

    public function mount()
    {
        $this->formTitle = 'Create Request';
        $this->formDescription = 'Create a Request to ask something from us';
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

    function fields(): Fields
    {
        return new Fields(
            Select::make('category')
                ->options(RequestCategory::all())
                ->blank()
                ->outsideGrid()
                ->labelPosition(FieldLabelPosition::TOP),
            Select::make('item')
                ->options(RequestCategory::find($this->category) ? RequestCategory::find($this->category)->items()->get() : [])
                ->blank()
                ->outsideGrid()
                ->labelPosition(FieldLabelPosition::TOP),
            TextArea::make('description')
                ->label('Please describe what do you need')
                ->outsideGrid()
                ->labelPosition(FieldLabelPosition::TOP),
        );
    }
}
