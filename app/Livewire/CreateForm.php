<?php

namespace App\Livewire;

use App\Traits\HasFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

abstract class CreateForm extends Form
{
    public function render()
    {
        return view('livewire.create-form');
    }
}
