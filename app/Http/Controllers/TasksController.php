<?php

namespace App\Http\Controllers;

use App\Models\Task;

class TasksController extends Controller
{
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);

        $this->authorize('edit', $task);

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }
}
