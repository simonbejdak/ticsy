<?php

namespace App\Models;

use App\Interfaces\Activitable;
use App\Interfaces\Fieldable;
use App\Interfaces\Slable;
use App\Models\Incident\IncidentStatus;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Models\Request\RequestOnHoldReason;
use App\Models\Request\RequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

abstract class Ticket extends Model implements Slable, Fieldable, Activitable
{
    use LogsActivity;

    protected $guarded = [];
    protected $appends = ['sla'];

    const ARCHIVE_AFTER_DAYS = 3;
    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;
    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function category(): BelongsTo
    {
        return $this->belongsTo($this->defineCategoryClass(), 'category_id');
    }

    function item(): BelongsTo
    {
        return $this->belongsTo($this->defineItemClass(), 'item_id');
    }

    function status(): BelongsTo
    {
        return $this->belongsTo($this->defineStatusClass(), 'status_id');
    }

    function onHoldReason(): BelongsTo
    {
        return $this->belongsTo($this->defineOnHoldReasonClass(), 'on_hold_reason_id');
    }

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    function slas(): MorphMany
    {
        return $this->morphMany(Sla::class, 'slable');
    }

    function isStatus(...$statuses): bool
    {
        foreach ($statuses as $status){
            if($this->status_id == $this->defineStatusClass()::MAP[$status]){
                return true;
            }
        }
        return false;
    }

    abstract function defineCategoryClass(): string;
    abstract function defineItemClass(): string;
    abstract function defineStatusClass(): string;
    abstract function defineOnHoldReasonClass(): string;

    abstract function isArchived(): bool;

    function calculateSlaMinutes(): int
    {
        return self::PRIORITY_TO_SLA_MINUTES[$this->priority];
    }

    function getSlaAttribute(): Sla
    {
        return $this->slas->last();
    }

    function statusChanged(): bool
    {
        return $this->isDirty('status_id');
    }

    function statusChangedTo(string $status): bool
    {
        return $this->statusChanged() && $this->isStatus($status);
    }

    function statusChangedFrom(string $status): bool
    {
        return $this->statusChanged() && $this->getOriginal('status_id') == RequestStatus::MAP[$status];
    }

    function priorityChanged(): bool
    {
        return $this->isDirty('priority');
    }

    abstract function isFieldModifiable(string $name): bool;

    function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'category.name',
                'item.name',
                'description',
                'status.name',
                'onHoldReason.name',
                'priority', 'group.name',
                'resolver.name',
            ])
            ->logOnlyDirty();
    }
}
