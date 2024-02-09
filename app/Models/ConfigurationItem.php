<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfigurationItem extends Model
{
    use HasFactory;

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
