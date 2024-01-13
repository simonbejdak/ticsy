<?php

namespace App\Services;

use App\Enums\TaskSequence;
use App\Interfaces\Taskable;
use App\Interfaces\Ticket;
use App\Models\Request;
use App\Enums\Status;
use App\Models\User;

class TaskableService
{
    static function setTasks(Taskable $taskable): void
    {
        $taskPlan = $taskable->taskPlan();

        foreach($taskPlan->tasks as $description){
            TaskService::createTask($description, $taskable);
        }

        if($taskPlan->sequence == TaskSequence::GRADUAL){
            TaskService::startTask($taskable->tasks->first());
        } elseif($taskPlan->sequence == TaskSequence::AT_ONCE){
            foreach ($taskable->tasks as $task){
                TaskService::startTask($task);
            }
        }
    }

    static function eventTaskablePriorityChanged(Taskable $taskable): void
    {
        foreach ($taskable->tasks as $task){
            $task->priority = $taskable->priority;
            $task->save();
        }
    }

    static function eventTaskResolved(Taskable $taskable): void
    {
        if($taskable->hasNonStartedTask()){
            self::startNextTask($taskable);
        } elseif($taskable->hasAllTasksClosed()) {
            TicketService::resolveTicket($taskable);
        }
    }

    public static function eventTaskCancelled(Taskable $taskable): void
    {
        TicketService::cancelTicket($taskable);
    }

    static function startNextTask(Taskable $taskable): void
    {
        TaskService::startTask($taskable->tasks()->notStarted()->first());
    }
}
