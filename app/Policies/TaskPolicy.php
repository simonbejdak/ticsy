<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('view_all_tickets');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update_all_tickets');
    }

    public function setPriorityOne(User $user): bool
    {
        return $user->hasPermissionTo('set_priority_one');
    }

    public function addComment(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('add_comments_to_all_tickets');
    }
}
