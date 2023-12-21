<?php

namespace App\Models\Request;

use App\Models\Enum;
use App\Models\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestItem extends Enum
{
    use HasFactory;
    const MAP = [
        'access' => 1,
        'backup' => 2,
    ];

    const ACCESS = self::MAP['access'];
    const BACKUP = self::MAP['backup'];

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class, 'item_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(RequestCategory::class, 'request_categories_request_items', 'category_id', 'item_id');
    }
}
