<?php

namespace App\Helpers;

use App\Enums\TaskSequence;

class TaskList
{
    public array $tasks;
    public TaskSequence $sequence;

    public function __construct(TaskSequence $sequence = TaskSequence::GRADUAL)
    {
        $this->sequence = $sequence;
    }

    function addTask(string $description): self
    {
        $this->tasks[] = $description;
        return $this;
    }
}
