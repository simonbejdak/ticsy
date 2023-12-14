<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestCategory extends MappableModel
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

    function requests(): hasMany{
        return $this->hasMany(Request::class, 'category_id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'request_categories_request_items', 'category_id', 'item_id');
    }

    public function hasItem(RequestItem $item): bool
    {
        if(count($this->items()->where('id', '=', $item->id)->get()) == 0){
            return true;
        }
        return false;
    }

    public function randomItem()
    {
        return $this->items()->inRandomOrder()->first();
    }

    function getNameAttribute($value): string
    {
        return ucfirst($value);
    }
}
