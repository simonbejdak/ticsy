<?php

namespace App\Models;

use App\Livewire\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableConfiguration extends Model
{
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function scopeByTable(Builder $query, Table $table): Builder
    {
        return $query->where('table_name', '=', get_class_name($table));
    }
}
