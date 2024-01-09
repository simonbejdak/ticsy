<?php

namespace App\Observers;

use App\Enums\Status;
use App\Models\Task;
use App\Services\RequestService;

class TaskObserver
{
    function creating(Task $task): void
    {
        $task->caller_id = $task->request->caller_id;
        $task->priority = $task->request->priority;
    }

    function updated(Task $task): void
    {
        if($task->statusChangedTo(Status::RESOLVED)){
            RequestService::eventTaskResolved($task->request);
        }
        if($task->statusChangedTo(Status::CANCELLED)){
            RequestService::eventTaskCancelled($task->request);
        }
    }
}
