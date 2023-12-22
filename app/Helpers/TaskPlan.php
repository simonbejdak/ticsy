<?php

namespace App\Helpers;

use App\Enums\TaskSequence;

class TaskPlan
{
    public array $tasks;
    public TaskSequence $sequence;

    public function __construct(TaskSequence $sequence = TaskSequence::GRADUAL)
    {
        $this->tasks = [];
        $this->sequence = $sequence;
    }

    function addTask(string $description): self
    {
        $this->tasks[] = $description;
        return $this;
    }

    function setSequence(TaskSequence $sequence): self
    {
        $this->sequence = $sequence;
        return $this;
    }
}
