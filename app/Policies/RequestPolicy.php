<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;

class RequestPolicy
{
    public function __construct()
    {

    }

    public function setRequestOnHoldReason(User $user): bool
    {
        return $user->hasPermissionTo('set_request_on_hold_reason');
    }
}
