<?php

namespace App\Services;

use App\Enums\TaskSequence;
use App\Interfaces\Ticket;
use App\Models\Request;
use App\Models\Status;
use App\Models\User;

class RequestService
{
    static function setTasks(Request $request): void
    {
        $taskList = $request->taskList();
        foreach($taskList->tasks as $task){
            TaskService::createTask($request, $task);
        }
        if($taskList->sequence == TaskSequence::GRADUAL){
            TaskService::startTask($request->tasks()->first());
        } elseif($taskList->sequence == TaskSequence::AT_ONCE){
            foreach ($request->tasks as $task){
                TaskService::startTask($task);
            }
        }
    }

    static function resolveRequest(Request $request): void
    {
        $request->status_id = Status::RESOLVED;
        $request->save();
    }

    static function eventTaskResolved(Request $request): void
    {
        if(empty($request->tasks()->notStarted()->get())){
            RequestService::resolveRequest($request);
        } else {
            self::startNextTask($request);
        }
    }

    static function startNextTask(Request $request): void
    {
        TaskService::startTask($request->tasks()->notStarted()->first());
    }
}
