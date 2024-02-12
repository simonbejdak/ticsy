<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;

interface Entity
{
    function isArchived(): bool;
    function activities(): MorphMany;
    function getActivityLogOptions(): LogOptions;
}
