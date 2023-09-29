<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Ticket
{
    protected $table = 'tickets';
    const CATEGORIES = [
        'network',
        'server',
        'computer',
        'application',
        'email'
    ];

    public static function boot()
    {
        parent::boot();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
