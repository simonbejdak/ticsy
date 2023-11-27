<?php

namespace App\View\Components;

use App\Livewire\TicketForm;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;

class FieldSelect extends FieldElement
{
    public function render(): View|Closure|string
    {
        return view('components.field-select');
    }
}
