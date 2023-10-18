<?php

namespace App\View\Components;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class TicketFieldResolver extends Component
{
    public Ticket $ticket;
    public string $name;
    public string $selected;
    public Collection $resolvers;
    public bool $required;
    public bool $disabled;
    public function __construct(Ticket $ticket){
        $this->ticket = $ticket;
        $this->name = 'resolver';
        $this->selected = ($this->ticket->resolver->name) ?? '';
        $this->resolvers = User::role('resolver')->get();
        $this->required = false;
        $this->disabled = $this->isDisabled();
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-resolver');
    }

    public function isDisabled(): bool
    {
        if(auth()->user()->cannot('setResolver', $this->ticket)){
            return true;
        }
        if($this->ticket->isArchived()){
            return true;
        };

        return false;
    }
}
