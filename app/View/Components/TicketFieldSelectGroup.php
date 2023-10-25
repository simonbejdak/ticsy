<?php

namespace App\View\Components;

use App\Models\Group;
use App\Models\Status;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class TicketFieldSelectGroup extends TicketFieldSelect
{
    public function __construct(Ticket $ticket){
        parent::__construct();

        $this->ticket = $ticket;
        $this->name = 'group';
        $this->options = $this->toIterable(Group::all());
        $this->required = true;
        $this->disabled = $this->isDisabled();
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-select');
    }

    public function isDisabled(): bool
    {
        if(auth()->user()->cannot('setGroup', $this->ticket)){
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
