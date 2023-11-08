<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends MappableModel
{
    use HasFactory;

    const MAP = [
        'issue' => 1,
        'computer_is_too_slow' => 2,
        'application_error' => 3,
        'backup' => 4,
        'failed_node' => 5,
        'failure' => 6,
    ];

    const ISSUE = self::MAP['issue'];
    const COMPUTER_IS_TOO_SLOW = self::MAP['computer_is_too_slow'];
    const APPLICATION_ERROR = self::MAP['application_error'];
    const BACKUP = self::MAP['backup'];
    const FAILED_NODE = self::MAP['failed_node'];
    const FAILURE = self::MAP['failure'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getNameAttribute($value)
    {
        $value = str_replace('_',' ', $value);
        $value = ucwords($value);

        return $value;
    }
}
