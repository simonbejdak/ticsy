<?php

namespace App\View\Components;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class TicketFieldPriority extends Component
{
    public Ticket $ticket;
    public string $name;
    public array $priorities;
    public bool $required;
    public bool $disabled;
    public function __construct(Ticket $ticket){
        $this->ticket = $ticket;
        $this->name = 'priority';
        $this->priorities = TicketConfiguration::PRIORITIES;
        $this->required = true;
        $this->disabled = $this->isDisabled();
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-priority');
    }

    public function isDisabled(): bool
    {
        if(auth()->user()->cannot('setPriority', $this->ticket)){
            return true;
        }
        if($this->ticket->isResolved()){
            return true;
        }
        if($this->ticket->isArchived()){
            return true;
        };

        return false;
    }
}
