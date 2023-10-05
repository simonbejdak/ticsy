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

    public function show(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }

    public function edit(User $user, Ticket $ticket)
    {
        return ($user->id === $ticket->user_id || $user->is_resolver);
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }

    public function destroy(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }

    public function setPriority(User $user, Ticket $ticket)
    {
        return (bool) $user->can_change_priority;
    }

    public function setResolver(User $user, Ticket $ticket)
    {
        return (bool) $user->is_resolver;
    }
}
