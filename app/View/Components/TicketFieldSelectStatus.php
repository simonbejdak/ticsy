<?php

namespace App\View\Components;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use function PHPUnit\Framework\isInstanceOf;

class TicketFieldSelectStatus extends TicketFieldSelect
{
    public function __construct(Ticket $ticket, Collection $options){
        parent::__construct('status', $options, $ticket);
    }

    public function isDisabled(): bool
    {
        if(auth()->user()->cannot('setStatus', $this->ticket)){
            return true;
        }
        if($this->ticket->isArchived()){
            return true;
        };

        return false;
    }
}
