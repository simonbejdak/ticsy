<?php

namespace App\Helpers\Strategies;

use App\Models\Task;

class TaskStrategy extends TicketStrategy
{
    static function create(Task $task): self
    {
        return new static();
    }
}
