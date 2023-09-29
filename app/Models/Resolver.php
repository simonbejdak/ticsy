<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolver extends User
{
    protected $table = 'users';

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

    public function canChangePriority(): bool
    {
        return (bool) $this->can_change_priority;
    }
}
