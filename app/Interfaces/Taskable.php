<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;

interface Taskable
{
    public function tasks(): MorphMany;
    public function editFormRoute(): string;
}
