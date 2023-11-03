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
        return ($user->id === $ticket->user_id || $user->hasPermissionTo('view_all_tickets'));
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }

    public function destroy(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }

    public function setCategory(User $user)
    {
        return (bool) $user->can('set_category');
    }

    public function setItem(User $user)
    {
        return (bool) $user->can('set_item');
    }

    public function setDescription(User $user)
    {
        return (bool) $user->can('set_description');
    }

    public function setPriority(User $user, Ticket $ticket)
    {
        return (bool) $user->can('set_priority');
    }
    public function setGroup(User $user, Ticket $ticket)
    {
        return (bool) $user->can('set_group');
    }
    public function setResolver(User $user, Ticket $ticket)
    {
        return (bool) $user->can('set_resolver');
    }
    public function setStatus(User $user, Ticket $ticket)
    {
        return (bool) $user->can('set_status');
    }


    public function addComment(User $user, Ticket $ticket)
    {
        return (bool) ($user->id === $ticket->user_id || $user->hasPermissionTo('add_comments_to_all_tickets'));
    }
}
