<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait Entity
{
    use LogsActivity;

    function isArchived(): bool
    {
        return false;
    }

    abstract function getActivityLogOptions(): LogOptions;
}
