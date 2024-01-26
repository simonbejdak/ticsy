<?php

namespace App\Services;

use App\Enums\Status;
use App\Interfaces\Taskable;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TaskService
{
    public static function createTask(string $description, Taskable $taskable = null): void
    {
        $task = new Task();
        if($taskable){
            $task->taskable()->associate($taskable);
        }
        $task->description = $description;
        $task->save();
    }

    static function startTask(Task $task): void
    {
        $task->started_at = Carbon::now();
        $task->save();
    }

    static function resolveTask(Task $task): void
    {
        $task->status = Status::RESOLVED;
        $task->save();
    }

    static function cancelTask(Task $task): void
    {
        $task->status = Status::CANCELLED;
        $task->save();
    }

}
