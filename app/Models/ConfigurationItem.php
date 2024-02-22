<?php

namespace App\Models;

use App\Enums\ConfigurationItemStatus;
use App\Enums\ConfigurationItemType;
use App\Enums\Location;
use App\Enums\OperatingSystem;
use App\Traits\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;

class ConfigurationItem extends Model
{
    use Entity, HasFactory;

    protected $casts = [
        'location' => Location::class,
        'operating_system' => OperatingSystem::class,
        'status' => ConfigurationItemStatus::class,
        'type' => ConfigurationItemType::class,
    ];

    protected $attributes = [
        'group_id' => Group::SERVICE_DESK_ID,
    ];

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function isArchived(): bool
    {
        return $this->status == ConfigurationItemStatus::RETIRED;
    }

    function scopePrimary(Builder $query): void
    {
        $query->where('type', '=', ConfigurationItemType::PRIMARY->value);
    }

    function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user.name',
                'group.name',
                'location',
                'status',
                'type',
                'serial_number',
                'operating_system',
            ])
            ->logOnlyDirty();
    }
}
