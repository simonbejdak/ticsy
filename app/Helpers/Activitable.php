<?php

namespace App\Helpers;

use Spatie\Activitylog\LogOptions;

interface Activitable
{
    public function getActivityLogOptions(): LogOptions;
}
