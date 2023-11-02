<?php

namespace App\View\Components;

use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;

class TicketField extends Component
{
    public string $name;
    public $value;
    public bool $disabled = false;

    public function __construct(bool $disabled = false, string $name = '', $value = '')
    {
        $this->disabled = $disabled;
        $this->name = $name;
        $this->value = $value;
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field');
    }
}
