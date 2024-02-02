<?php

namespace App\Models;

use App\Enums\ResolverPanelOption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoriteResolverPanelOption extends Model
{
    use HasFactory;

    protected $casts = [
        'option' => ResolverPanelOption::class,
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
