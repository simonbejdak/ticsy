<?php

namespace App\Policies;

use App\Models\Resolver;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\User;

class TicketPolicy
{
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

    public function setCategory(User $user, Ticket|null $ticket): bool
    {
        return ($user->hasPermissionTo('set_category') && !$ticket->exists);
    }

    public function setItem(User $user, Ticket|null $ticket): bool
    {
        return ($user->hasPermissionTo('set_item') && !$ticket->exists);
    }

    public function setDescription(User $user, Ticket|null $ticket): bool
    {
        return ($user->hasPermissionTo('set_description') && !$ticket->exists);
    }

    public function setStatus(User $user, Ticket $ticket): bool
    {
        return ($user->hasPermissionTo('set_status') && !$ticket->isArchived());
    }

    public function setOnHoldReason(User $user, Ticket $ticket): bool
    {
        return (
            $user->hasPermissionTo('set_on_hold_reason')
            && !$ticket->isArchived()
            && $ticket->isStatus('on_hold')
        );
    }

    public function setPriorityOne(User $user, Ticket $ticket): bool
    {
        return ($user->hasPermissionTo('set_priority_one') && !$ticket->isResolved() && !$ticket->isArchived());
    }

    public function setPriority(User $user, Ticket $ticket): bool
    {
        return (($user->hasPermissionTo('set_priority') || ($user->hasPermissionTo('set_priority_one'))) && !$ticket->isResolved() && !$ticket->isArchived());
    }

    public function setGroup(User $user, Ticket $ticket): bool
    {
        return ($user->hasPermissionTo('set_group') && !$ticket->isResolved() && !$ticket->isArchived());
    }

    public function setResolver(User $user, Ticket $ticket): bool
    {
        $resolver = $ticket->resolver;

        if($resolver !== null){
            $isResolverValid = $resolver->isGroupMember($ticket->group);
        }
        if($resolver === null){
            $isResolverValid = true;
        }

        return ($user->hasPermissionTo('set_resolver')
            && !$ticket->isResolved()
            && !$ticket->isArchived()
            && $isResolverValid
        );
    }

    public function addComment(User $user, Ticket $ticket): bool
    {
        return ($user->id === $ticket->user_id || $user->hasPermissionTo('add_comments_to_all_tickets'));
    }
}
