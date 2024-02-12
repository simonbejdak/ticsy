<?php

namespace App\Livewire;

use App\Enums\FieldLabelPosition;
use App\Helpers\Fields\Fields;

abstract class CreateForm extends Form
{
    public $formTitle;
    public $formDescription;

    public function render()
    {
        return view('livewire.create-form');
    }

    protected function fields(): Fields
    {
        $fields = new Fields();
        foreach ($this->schema() as $field){
            $fields->add(
                $field->outsideGrid()
                    ->disabledIf($this->isFieldDisabled($field->name))
                    ->labelPosition(FieldLabelPosition::TOP)
            );
        }
        return $fields;
    }
}
