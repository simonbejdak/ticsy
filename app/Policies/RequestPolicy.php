<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;

class RequestPolicy
{
    public function __construct()
    {

    }

    public function edit(User $user, Request $request): bool
    {
        return ($user->id === $request->caller_id || $user->hasPermissionTo('view_all_tickets'));
    }

    public function setRequestOnHoldReason(User $user): bool
    {
        return $user->hasPermissionTo('set_request_on_hold_reason');
    }
}
