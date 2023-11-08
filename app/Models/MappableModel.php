<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class MappableModel extends Model
{
    const MAP = [];

    public static function count()
    {
        return count(static::MAP);
    }
}
