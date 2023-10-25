<?php

namespace App\View\Components;

use App\Models\Group;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class TicketFieldSelectResolver extends TicketFieldSelect
{
    public Group $group;
    public function __construct(Ticket $ticket, int $group){
        parent::__construct();

        $this->ticket = $ticket;
        $this->name = 'resolver';
        $this->group = Group::findOrFail($group);
        $this->options = $this->toIterable($this->group->resolvers()->get());
        $this->required = false;
        $this->disabled = $this->isDisabled();
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-select');
    }

    public function isDisabled(): bool
    {
        if(auth()->user()->cannot('setResolver', $this->ticket)){
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
