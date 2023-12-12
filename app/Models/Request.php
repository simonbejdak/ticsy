<?php

namespace App\Models;

use App\Helpers\Slable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Request extends Model implements Slable
{
    use HasFactory;

    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => 4,
    ];

    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;
    const DEFAULT_STATUS = RequestStatus::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;

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

    function calculateSlaMinutes(): int
    {
        return 30;
    }

    public function isStatus(...$statuses): bool{
        foreach ($statuses as $status){
            if($this->status_id == RequestStatus::MAP[$status]){
                return true;
            }
        }
        return false;
    }
}
