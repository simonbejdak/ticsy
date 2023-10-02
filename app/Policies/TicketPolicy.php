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

    public function edit(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }

    public function destroy(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }
}
