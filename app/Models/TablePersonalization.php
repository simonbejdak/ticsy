<?php

namespace App\Models;

use App\Livewire\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TablePersonalization extends Model
{
    protected $guarded = [];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function scopeByTable(Builder $query, Table $table): Builder
    {
        return $query->where('table_name', '=', get_class_name($table));
    }
}
