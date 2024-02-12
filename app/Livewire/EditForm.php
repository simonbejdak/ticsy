<?php

namespace App\Livewire;

use App\Enums\FieldLabelPosition;
use App\Helpers\Fields\Fields;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;

abstract class EditForm extends Form
{
    #[Locked]
    public Collection $activities;

    public function render()
    {
        return view('livewire.edit-form');
    }

    protected function setActivities(): void
    {
        $this->activities = $this->model->activities()->where('properties', '!=', '{"attributes":[],"old":[]}')->orderByDesc('id')->get();
    }

    protected function fields(): Fields
    {
        $fields = new Fields();
        foreach ($this->schema() as $field){
            $fields->add(
                $field->disabledIf($this->isFieldDisabled($field->name))
                    ->labelPosition(FieldLabelPosition::LEFT)
            );
        }
        return $fields;
    }
}
