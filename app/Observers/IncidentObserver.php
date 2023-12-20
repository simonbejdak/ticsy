<?php

namespace App\Observers;

use App\Interfaces\Ticket;
use Exception;

class IncidentObserver
{
    public function creating(Ticket $ticket): void
    {
        if ($ticket->category->hasItem($ticket->item)){
            throw new Exception('Item cannot be assigned to TicketTrait if it does not match Category');
        }
    }
}
