<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolver extends User
{
    protected $table = 'users';

    protected $attributes = [
        'can_change_priority' => false,
    ];

    protected $casts = [
        'can_change_priority' => 'boolean',
    ];

    use HasFactory;

    public static function boot()
    {
        parent::boot();
    }


    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
