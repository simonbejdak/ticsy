<?php

namespace App\Helpers\Strategies;

use App\Enums\TaskSequence;
use App\Helpers\TaskPlan;
use App\Models\Group;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;

abstract class TaskableStrategy extends TicketStrategy
{
    public array $tasks;
    public TaskSequence $taskSequence;

    protected function __construct(){
        $this->taskSequence = TaskSequence::GRADUAL;
        parent::__construct();
    }
}
