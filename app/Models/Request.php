<?php

namespace App\Models;

use App\Helpers\Slable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

class Request extends Model implements Slable
{
    use HasFactory;

    const ARCHIVE_AFTER_DAYS = 3;
    const DEFAULT_PRIORITY = 4;
    const DEFAULT_STATUS = RequestStatus::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;
    const PRIORITIES = [1, 2, 3, 4];
    const PRIORITY_SLA = [
        1 => 15,
        2 => 30,
        3 => 6 * 60,
        4 => 12 * 60
    ];

    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => self::DEFAULT_PRIORITY,
    ];

    protected $appends = ['sla'];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    function category(): BelongsTo
    {
        return $this->belongsTo(RequestCategory::class, 'category_id');
    }

    function item(): BelongsTo
    {
        return $this->belongsTo(RequestItem::class, 'item_id');
    }

    function status(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class, 'status_id');
    }

    function onHoldReason(): BelongsTo
    {
        return $this->belongsTo(RequestOnHoldReason::class, 'on_hold_reason_id');
    }

    function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolver_id');
    }

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    function slas(): MorphMany
    {
        return $this->morphMany(Sla::class, 'slable');
    }

    function getSlaAttribute(): Sla
    {
        return $this->slas->last();
    }

    function calculateSlaMinutes(): int
    {
        return self::PRIORITY_SLA[$this->priority];
    }

    public function isArchived(): bool{
        if($this->getOriginal('status_id') == RequestStatus::CLOSED){
            $archivalDate = $this->closed_at->addDays(self::ARCHIVE_AFTER_DAYS);
            if(isset($this->closed_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status_id') == RequestStatus::CANCELLED;
    }

    public function isStatus(...$statuses): bool{
        foreach ($statuses as $status){
            if($this->status_id == RequestStatus::MAP[$status]){
                return true;
            }
        }
        return false;
    }

    public function statusChanged(): bool
    {
        return $this->isDirty('status_id');
    }

    public function statusChangedTo(string $status): bool
    {
        return $this->statusChanged() && $this->isStatus($status);
    }

    public function statusChangedFrom(string $status): bool
    {
        return $this->statusChanged() && $this->getOriginal('status_id') == RequestStatus::MAP[$status];
    }
}
