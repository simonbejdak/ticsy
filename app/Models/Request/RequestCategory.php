<?php

namespace App\Models\Request;

use App\Models\Enum;
use App\Models\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestCategory extends Enum
{
    use HasFactory;

    const MAP = [
        'server' => 1,
        'computer' => 2,
    ];

    const SERVER = self::MAP['server'];
    const COMPUTER = self::MAP['computer'];

    function requests(): hasMany{
        return $this->hasMany(Request::class, 'category_id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(RequestItem::class, 'request_categories_request_items', 'category_id', 'item_id');
    }

    public function hasItem(RequestItem $item): bool
    {
        if(count($this->items()->where('id', '=', $item->id)->get()) > 0){
            return true;
        }
        return false;
    }

    public function getItemIds(): array
    {
        return $this->items()->select('id')->get()->pluck('id')->toArray();
    }

    public function randomItem()
    {
        return $this->items()->inRandomOrder()->first();
    }
}
