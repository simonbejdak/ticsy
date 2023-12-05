<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class ActivityService
{
    public static function comment(Model $model, string $body): void
    {
        activity()
            ->performedOn($model)
            ->causedBy(auth()->user())
            ->event('comment')
            ->log($body);
    }

    public static function priorityChangeReason(Model $model, string $body)
    {
        activity()
            ->performedOn($model)
            ->causedBy(auth()->user())
            ->event('priority_change_reason')
            ->log($body);
    }
}
