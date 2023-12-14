<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function edit(User $user, Ticket $ticket): bool
    {
        return ($user->id === $ticket->caller_id || $user->hasPermissionTo('view_all_tickets'));
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update_all_tickets');
    }

    public function setPriorityOne(User $user): bool
    {
        return $user->hasPermissionTo('set_priority_one');
    }

    public function addComment(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->caller_id || $user->hasPermissionTo('add_comments_to_all_tickets');
    }
}
