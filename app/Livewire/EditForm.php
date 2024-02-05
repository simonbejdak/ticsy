<?php

namespace App\Livewire;

use App\Traits\HasFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

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
}
