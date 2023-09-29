<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const TYPES = [
        'incident' => 1,
        'request' => 2,
        'change' => 3,
    ];

    const CATEGORIES = [
        'network' => 1,
        'server' => 2,
        'computer' => 3,
        'application' => 4,
        'email' => 5,
    ];

    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;

    use HasFactory;

    protected $guarded = [];
    protected $attributes = [
        'priority' => self::DEFAULT_PRIORITY,
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

}
