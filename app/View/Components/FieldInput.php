<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FieldInput extends Component
{
    public string $name;
    public string $value;
    public string $style;
    public bool $disabled;
    public bool $error;
    public bool $anchor;

    public function __construct(
        string $name = '',
        string $value = '',
        string $style = '',
        bool $disabled = false,
        bool $error = false,
        bool $anchor = false
    ){
        $this->name = $name;
        $this->value = $value;
        $this->style = $style;
        $this->disabled = $disabled;
        $this->error = $error;
        $this->anchor = $anchor;
    }

    public function render(): View|Closure|string
    {
        return view('components.field-input');
    }
}
