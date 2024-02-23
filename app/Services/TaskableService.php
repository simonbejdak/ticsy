<?php

namespace App\Services;

use App\Enums\TaskSequence;
use App\Interfaces\Taskable;

class TaskableService
{

    static function setTasks(Taskable $taskable): void
    {
        $strategy = $taskable->strategy();

        foreach($strategy->tasks as $task){
            TaskService::createTask($task, $taskable, $strategy->group);
        }

        if($strategy->taskSequence == TaskSequence::GRADUAL){
            TaskService::startTask($taskable->tasks->first());
        } elseif($strategy->taskSequence == TaskSequence::AT_ONCE){
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
