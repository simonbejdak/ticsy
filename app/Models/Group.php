<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    const GROUPS = [
        'SERVICE-DESK' => 1,
        'LOCAL-6445-NEW-YORK' => 2,
    ];

    const DEFAULT = self::GROUPS['SERVICE-DESK'];

    public function resolvers()
    {
        return $this->belongsToMany(User::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
