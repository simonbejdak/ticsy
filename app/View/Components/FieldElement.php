<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FieldElement extends Component
{
    public string $name;
    public string $value;
    public string $style;
    public bool $disabled;
    public bool $error;
    public bool $anchor;

    public function __construct(string $name = '', string $value = '', bool $disabled = false, bool $error = false, bool $anchor = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->disabled = $disabled;
        $this->error = $error;
        $this->anchor = $anchor;

        $this->style =
            ($this->disabled ? ' text-gray-500 bg-slate-200 ' : ' bg-white ') .
            ($this->anchor ? ' hover:cursor-pointer hover:border-gray-400 transform ease-in duration-150 ' : ' ') .
            ($this->disabled && !$this->anchor ? ' pointer-events-none ' : ' caret-gray-200 ') .
            ($this->error ? ' ring-2 ring-red-500 ' : ' ') .
            ' appearance-none px-2 w-full pt-2 pb-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 ';
    }

    public function render(): View|Closure|string
    {
        return view('components.field-select');
    }
}
