<?php

namespace App\Models;

use App\Interfaces\Activitable;
use App\Interfaces\Fieldable;
use App\Interfaces\Slable;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\Task;

abstract class Ticket extends Model implements Slable, Fieldable, Activitable
{
    use LogsActivity, Task;

    protected $guarded = [];
    protected $appends = ['sla'];

    const ARCHIVE_AFTER_DAYS = 3;
    const PRIORITIES = [1, 2, 3, 4];
    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::observe( TicketObserver::class);
    }

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

    function slas(): MorphMany
    {
        return $this->morphMany(Sla::class, 'slable');
    }

    function isStatus(...$statuses): bool
    {
        foreach ($statuses as $status){
            if($this->status_id == Status::MAP[$status]){
                return true;
            }
        }
        return false;
    }

    abstract function defineCategoryClass(): string;
    abstract function defineItemClass(): string;

    public function isArchived(): bool{
        if($this->getOriginal('status_id') == Status::RESOLVED){
            $archivalDate = $this->resolved_at->addDays(self::ARCHIVE_AFTER_DAYS);
            if(isset($this->resolved_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status_id') == Status::CANCELLED;
    }

    function calculateSlaMinutes(): int
    {
        return $this::PRIORITY_TO_SLA_MINUTES[$this->priority];
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
        return $this->statusChanged() && $this->getOriginal('status_id') == Status::MAP[$status];
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
                auth()->user()->can('update', self::class) && !$this->isStatus('resolved'),
            'priorityChangeReason' =>
                auth()->user()->can('update', self::class) &&
                $this->priorityChanged() &&
                !$this->isStatus('resolved'),
            'resolver' =>
                auth()->user()->can('update', self::class) &&
                !$this->isStatus('resolved') &&
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
                'priority',
                'group.name',
                'resolver.name',
            ])
            ->logOnlyDirty();
    }
}
