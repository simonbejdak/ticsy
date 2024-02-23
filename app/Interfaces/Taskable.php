<?php

namespace App\Interfaces;

use App\Helpers\Strategies\TaskableStrategy;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Taskable
{
    public function tasks(): MorphMany;
    public function editRoute(): string;
    public function strategy(): TaskableStrategy;
}
