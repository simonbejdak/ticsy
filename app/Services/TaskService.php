<?php

namespace App\Services;

use App\Interfaces\Ticket;
use App\Models\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

class TaskService
{
    public static function createTask(Request $request, string $description): void
    {
        $task = new Task();
        $task->caller->id = $request->caller->id;
        $task->request->id = $request->id;
        $task->description = $description;
        $task->priority = $request->priority;
        $task->save();
    }

    static function startTask(Task $task): void
    {
        $task->started_at = Carbon::now();
        $task->save();
    }
}
