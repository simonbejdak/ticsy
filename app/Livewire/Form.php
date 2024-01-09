<?php

namespace App\Livewire;

use App\Traits\HasFields;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

abstract class Form extends Component
{
    public Model $model;

    public function updated($property): void
    {
        if(in_array(HasFields::class, class_uses_recursive($this))){
            if($this->isFieldDisabled($property)){
                abort(Response::HTTP_FORBIDDEN);
            }
        }
    }
}
