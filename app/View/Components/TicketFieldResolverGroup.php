<?php

namespace App\View\Components;

use App\Models\Group;
use App\Models\Status;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class TicketFieldResolverGroup extends Component
{
    public Ticket $ticket;
    public string $name;
    public string $selected;
    public Collection $groups;
    public bool $required;
    public bool $disabled;
    public function __construct(Ticket $ticket){
        $this->ticket = $ticket;
        $this->name = 'group';
        $this->selected = $this->ticket->group->name;
        $this->groups = Group::all();
        $this->required = true;
        $this->disabled = $this->isDisabled();
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-resolver-group');
    }

    public function isDisabled(): bool
    {
        if(auth()->user()->cannot('setGroup', $this->ticket)){
            return true;
        }
        if($this->ticket->isArchived()){
            return true;
        };

        return false;
    }
}
