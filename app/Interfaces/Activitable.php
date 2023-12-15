<?php

namespace App\Interfaces;

use Spatie\Activitylog\LogOptions;

interface Activitable
{
    public function getActivityLogOptions(): LogOptions;
}
