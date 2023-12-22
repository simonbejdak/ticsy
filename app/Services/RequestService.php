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
        $taskPlan = $request->taskPlan();

        foreach($taskPlan->tasks as $task){
            TaskService::createTask($request, $task);
        }

        if($taskPlan->sequence == TaskSequence::GRADUAL){
            TaskService::startTask($request->tasks->first());
        } elseif($taskPlan->sequence == TaskSequence::AT_ONCE){
            foreach ($request->tasks as $task){
                TaskService::startTask($task);
            }
        }
    }

    static function eventTaskResolved(Request $request): void
    {
        if(count(($request->tasks()->notStarted()->get())) > 0){
            self::startNextTask($request);
        } elseif(count($request->tasks()->notClosed()->get()) == 0) {
            TicketService::resolveTicket($request);
        }
    }

    public static function eventTaskCancelled(Request $request): void
    {
        TicketService::cancelTicket($request);
    }

    static function startNextTask(Request $request): void
    {
        TaskService::startTask($request->tasks()->notStarted()->first());
    }
}
