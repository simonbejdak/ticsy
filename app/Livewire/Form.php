<?php

namespace App\Livewire;

use App\Traits\HasFields;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

abstract class Form extends Component
{
    public function updated($property): void
    {
        if(in_array(HasFields::class, class_uses_recursive($this))){
            if($this->isFieldDisabled($property)){
                abort(Response::HTTP_FORBIDDEN);
            }
        }
    }
}
