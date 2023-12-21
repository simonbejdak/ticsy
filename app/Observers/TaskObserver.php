<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\RequestService;
use App\Services\TaskService;

class TaskObserver
{
    function updated(Task $task): void
    {
        if($task->statusChangedTo('resolved')){
            RequestService::eventTaskResolved($task->request);
        }
    }
}
