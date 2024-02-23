<?php

namespace App\Helpers\Strategies;

use App\Enums\TaskSequence;

abstract class TaskableStrategy extends TicketStrategy
{
    public array $tasks;
    public TaskSequence $taskSequence;

    protected function __construct(){
        $this->taskSequence = TaskSequence::GRADUAL;
    }
}
