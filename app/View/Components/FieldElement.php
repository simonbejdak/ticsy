<?php

namespace App\View\Components;

use App\Livewire\TicketForm;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;

class FieldElement extends Component
{
    public string $name;
    public string $value;
    public string $style;
    public bool $disabled;

    public function __construct(string $name = '', string $value = '', bool $disabled = false, bool $error = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->disabled = $disabled;

        $this->style =
            ($this->disabled ? ' text-gray-500 bg-slate-200 ' : ' bg-white ') .
            ($error ? ' ring-2 ring-red-500 ' : ' ') .
            ' appearance-none px-2 w-full pt-2 pb-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 ';
    }

    public function render(): View|Closure|string
    {
        return view('components.field-select');
    }
}
