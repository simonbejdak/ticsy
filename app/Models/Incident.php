<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Ticket
{
    protected $table = 'tickets';

    public static function boot()
    {
        parent::boot();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
