<?php

namespace App\Services;

use App\Interfaces\Ticket;
use App\Models\User;

class TaskService
{
    public static function createTask(Ticket $ticket, User $resolver): void
    {
        $ticket->resolver_id = $resolver->id;
        $ticket->save();
    }
}
