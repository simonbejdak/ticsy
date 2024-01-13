<?php

namespace App\Observers;

use App\Enums\TaskSequence;
use App\Models\Request;
use App\Services\TaskableService;
use App\Services\TaskService;
use Exception;

class RequestObserver
{
    function creating(Request $request): void
    {
        if (!$request->category->hasItem($request->item)){
            throw new Exception('Item cannot be assigned to '. get_class_name($request) .' if it does not match Category');
        }
    }

    function created(Request $request): void
    {
        TaskableService::setTasks($request);
    }

    function updated(Request $request): void
    {
        if($request->priorityChanged()){
            TaskableService::eventTaskablePriorityChanged($request);
        }
    }
}
