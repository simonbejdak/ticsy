<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;

class TicketService
{
    public static function assignTicket(Ticket $ticket, User $resolver): void
    {
        $ticket->resolver_id = $resolver->id;
        $ticket->save();
    }
}
