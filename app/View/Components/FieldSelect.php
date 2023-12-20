<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class FieldSelect extends FieldElement
{
    public function render(): View|Closure|string
    {
        return view('components.field-select');
    }
}
