<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;

class IncidentPolicy
{
    public function edit(User $user, Incident $ticket): bool
    {
        return ($user->id === $ticket->caller_id || $user->hasPermissionTo('view_all_tickets'));
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update_all_tickets');
    }

    public function setPriority(User $user): bool
    {
        return $user->hasPermissionTo('set_priority');
    }

    public function addComment(User $user, Incident $ticket): bool
    {
        return $user->id === $ticket->caller_id || $user->hasPermissionTo('add_comments_to_all_tickets');
    }
}
