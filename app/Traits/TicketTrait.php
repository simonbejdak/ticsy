<?php

namespace App\Traits;

use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Status;
use App\Models\User;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait TicketTrait
{
    use LogsActivity;

    protected static function bootTicketTrait(): void
    {
        static::observe(TicketObserver::class);
    }

    const DEFAULT_PRIORITY = 4;
    const DEFAULT_STATUS = Status::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;
    const ARCHIVE_AFTER_DAYS = 3;
    const PRIORITIES = [1, 2, 3, 4];

    function caller(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    abstract function category(): BelongsTo|HasOneThrough;
    abstract function item(): BelongsTo|HasOneThrough;

    function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    function onHoldReason(): BelongsTo
    {
        return $this->belongsTo(OnHoldReason::class);
    }

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
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

    function getNumberAttribute(): int
    {
        return $this->id;
    }

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
        return self::PRIORITY_TO_SLA_MINUTES[$this->priority];
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
