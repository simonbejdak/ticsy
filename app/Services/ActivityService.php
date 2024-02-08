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
}
