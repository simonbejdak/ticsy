<?php

namespace App\Models\Incident;

use App\Models\Enum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncidentCategory extends Enum
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

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'category_id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(IncidentItem::class, 'incident_category_incident_item', 'category_id', 'item_id');
    }

    public function randomItem()
    {
        return $this->items()->inRandomOrder()->first();
    }

    public function hasItem(IncidentItem $item): bool
    {
        if(count($this->items()->where('id', '=', $item->id)->get()) == 0){
            return true;
        }
        return false;
    }
}
