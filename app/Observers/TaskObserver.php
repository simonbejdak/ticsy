<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\RequestService;
use App\Services\TaskService;

class TaskObserver
{
    function creating(Task $task): void
    {
        $task->caller_id = $task->request->caller_id;
        $task->priority = $task->request->priority;
    }

    function updated(Task $task): void
    {
        if($task->statusChangedTo('resolved')){
            RequestService::eventTaskResolved($task->request);
        }
        if($task->statusChangedTo('cancelled')){
            RequestService::eventTaskCancelled($task->request);
        }
    }
}
