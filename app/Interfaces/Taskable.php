<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Taskable
{
    public function tasks(): MorphMany;
    public function editRoute(): string;
}
