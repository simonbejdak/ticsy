<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FieldLayout extends Component
{
    public \App\Helpers\Fields\Field $field;
    public bool $required;

    public function __construct(\App\Helpers\Fields\Field $field, bool $required = false){
        $this->field = $field;
        $this->required = $required;
    }

    public function render(): View|Closure|string
    {
        return view('components.field-layout');
    }
}
