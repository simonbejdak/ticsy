<?php

namespace App\Observers;

use App\Enums\Status;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskableService;

class TaskObserver
{
    function creating(Task $task): void
    {
        if($task->hasTaskable()){
            $task->caller_id = $task->taskable->caller_id;
            $task->priority = $task->taskable->priority;
        } else {
            $task->caller_id = User::getSystemUser()->id;
        }
    }

    function updated(Task $task): void
    {
        if($task->hasTaskable()) {
            if ($task->statusChangedTo(Status::RESOLVED)) {
                TaskableService::eventTaskResolved($task->taskable);
            }
            if ($task->statusChangedTo(Status::CANCELLED)) {
                TaskableService::eventTaskCancelled($task->taskable);
            }
        }
    }
}
