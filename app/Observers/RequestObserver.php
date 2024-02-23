<?php

namespace App\Observers;

use App\Models\Request;
use App\Services\TaskableService;
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
        $request->group_id = $request->strategy()->group->id;
        $request->save();
    }

    function updated(Request $request): void
    {
        if($request->priorityChanged()){
            TaskableService::eventTaskablePriorityChanged($request);
        }
    }
}
