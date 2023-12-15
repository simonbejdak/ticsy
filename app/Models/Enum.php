<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Enum extends Model
{
    const MAP = [];

    public static function count()
    {
        return count(static::MAP);
    }

    public function getNameAttribute($value): string
    {
        $value = str_replace('_', ' ', $value);
        $value = ucwords($value);

        return $value;
    }
}
