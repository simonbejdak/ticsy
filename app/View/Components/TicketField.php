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
    public Ticket|null $ticket;
    public string $name;
    public string $displayName;
    public $value;
    public bool $hideable;
    public bool $disabled;
    public bool $hidden;
    public string $styling;

    public function __construct(string $name, Ticket|null $ticket = null, bool $hideable = false, bool $disabled = null, $value = '')
    {
        $this->name = $name;
        $this->displayName = $this->setDisplayName($name);
        $this->ticket = $ticket;
        $this->hideable = $hideable;
        $this->disabled = $disabled !== null ? $disabled : $this->isDisabled();
        $this->hidden = $this->hideable && $this->disabled;
        $this->value = $value;
        $this->styling = ($this->disabled ? 'text-gray-500 bg-gray-200 ' : 'bg-white ') . 'rounded-lg border border-gray-300 w-full focus:border-indigo-500 focus:ring-indigo-500';
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field');
    }

    protected function isDisabled(): bool
    {
        return auth()->user()->cannot('set' . ucfirst($this->name), $this->ticket);
    }

    protected function setDisplayName($value): string{
        $value = $this->addSpacesBeforeUppercase($value);
        $value = strtolower($value);
        $value = ucfirst($value);

        return $value;
    }

    protected function addSpacesBeforeUppercase($value): string {
        return preg_replace('/([A-Z])/', ' $1', $value);
    }
}
