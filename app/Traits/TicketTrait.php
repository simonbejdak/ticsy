<?php

namespace App\Traits;

use App\Enums\Priority;
use App\Models\Group;
use App\Enums\OnHoldReason;
use App\Enums\Status;
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

    const DEFAULT_PRIORITY = Priority::FOUR;
    const DEFAULT_STATUS = Status::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK_ID;
    const ARCHIVE_AFTER_DAYS = 3;

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

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    function isStatus(Status ...$statuses): bool
    {
        return in_array($this->status, $statuses);
    }

    function getNumberAttribute()
    {
        return $this->id;
    }

    public function isArchived(): bool{
        if($this->getOriginal('status') == Status::RESOLVED){
            $archivalDate = $this->resolved_at->addDays(self::ARCHIVE_AFTER_DAYS);
            if(isset($this->resolved_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status') == Status::CANCELLED;
    }

    function calculateSlaMinutes(): int
    {
        return self::PRIORITY_TO_SLA_MINUTES[$this->priority->value];
    }

    function statusChanged(): bool
    {
        return $this->isDirty('status');
    }

    function statusChangedTo(Status $status): bool
    {
        return $this->statusChanged() && $this->isStatus($status);
    }

    function statusChangedFrom(Status $status): bool
    {
        return $this->statusChanged() && $this->getOriginal('status') == $status;
    }

    function priorityChanged(): bool
    {
        return $this->isDirty('priority');
    }

    function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'category.name',
                'item.name',
                'description',
                'status',
                'on_hold_reason',
                'priority',
                'group.name',
                'resolver.name',
            ])
            ->logOnlyDirty();
    }
}
