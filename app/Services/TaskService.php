<?php

namespace App\Services;

use App\Interfaces\Ticket;
use App\Models\Request;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

class TaskService
{
    public static function createTask(Request $request, string $description): void
    {
        $task = new Task();
        $task->request_id = $request->id;
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
        $task->status_id = Status::RESOLVED;
        $task->save();
    }

    static function cancelTask(Task $task): void
    {
        $task->status_id = Status::CANCELLED;
        $task->save();
    }

}
