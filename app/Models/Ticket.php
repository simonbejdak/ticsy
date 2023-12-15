<?php

namespace App\Models;

use App\Interfaces\Activitable;
use App\Interfaces\Fieldable;
use App\Interfaces\Slable;
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

    abstract function category(): BelongsTo;

    abstract function item(): BelongsTo;

    abstract function status(): BelongsTo;

    abstract function onHoldReason(): BelongsTo;

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
            if($this->status->name == $status){
                return true;
            }
        }
        return false;
    }

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

    function isFieldModifiable(string $name): bool
    {
        if($this->isArchived()){
            return false;
        }

        return match($name){
            'category', 'item', 'description' => !$this->exists,
            'status' => auth()->user()->can('update', self::class),
            'onHoldReason' =>
                auth()->user()->can('update', self::class) && $this->isStatus('on_hold'),
            'priority', 'group' =>
                auth()->user()->can('update', self::class) && !$this->isStatus('closed'),
            'priorityChangeReason' =>
                auth()->user()->can('update', self::class) &&
                $this->priorityChanged() &&
                !$this->isStatus('closed'),
            'resolver' =>
                auth()->user()->can('update', self::class) &&
                !$this->isStatus('closed') &&
                ($this->resolver == null || $this->resolver->isGroupMember($this->group)),
            default => false,
        };
    }

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
