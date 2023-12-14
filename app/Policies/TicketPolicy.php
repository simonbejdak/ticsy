<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function __construct()
    {

    }

    public function edit(User $user, Ticket $ticket)
    {
        return ($user->id === $ticket->caller_id || $user->hasPermissionTo('view_all_tickets'));
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->caller_id;
    }

    public function destroy(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->caller_id;
    }

    public function setCategory(User $user): bool
    {
        return $user->hasPermissionTo('set_category');
    }

    public function setItem(User $user): bool
    {
        return $user->hasPermissionTo('set_item');
    }

    public function setDescription(User $user): bool
    {
        return $user->hasPermissionTo('set_description');
    }

    public function setStatus(User $user): bool
    {
        return $user->hasPermissionTo('set_status');
    }

    public function setOnHoldReason(User $user): bool
    {
        return $user->hasPermissionTo('set_on_hold_reason');
    }

    public function setPriorityOne(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('set_priority_one') && !$ticket->isStatus('resolved') && !$ticket->isArchived();
    }

    public function setPriority(User $user): bool
    {
        return $user->hasPermissionTo('set_priority') || $user->hasPermissionTo('set_priority_one');
    }

    public function setPriorityChangeReason(User $user): bool
    {
        return $user->hasPermissionTo('set_priority_change_reason');
    }

    public function setGroup(User $user): bool
    {
        return $user->hasPermissionTo('set_group');
    }

    public function setResolver(User $user): bool
    {
        return $user->hasPermissionTo('set_resolver');
    }

    public function addComment(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->caller_id || $user->hasPermissionTo('add_comments_to_all_tickets');
    }
}
