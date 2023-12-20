<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Ticket;
use Exception;

class TaskObserver
{
    public function creating(Task $task): void
    {
        $task->priority = $task->request->priority;
    }
}
