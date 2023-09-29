<?php

namespace App\Policies;

use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function setPriority(Resolver $resolver, Ticket $ticket): bool
    {
        return $resolver->canChangePriority();
    }
}
