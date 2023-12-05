<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends MappableModel
{
    use HasFactory;

    const MAP = [
        'network' => 1,
        'server' => 2,
        'computer' => 3,
        'application' => 4,
        'email' => 5,
    ];

    const NETWORK = self::MAP['network'];
    const SERVER = self::MAP['server'];
    const COMPUTER = self::MAP['computer'];
    const APPLICATION = self::MAP['application'];
    const EMAIL = self::MAP['email'];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function randomItem()
    {
        return $this->items()->inRandomOrder()->first();
    }

    public function hasItem(Item $item): bool
    {
        if(count($this->items()->where('id', '=', $item->id)->get()) == 0){
            return true;
        }
        return false;
    }
}
