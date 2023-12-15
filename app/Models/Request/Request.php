<?php

namespace App\Models\Request;

use App\Models\Group;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;

class Request extends Ticket
{
    use HasFactory;

    const DEFAULT_STATUS = RequestStatus::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;
    const PRIORITY_TO_SLA_MINUTES = [
        1 => 15,
        2 => 30,
        3 => 6 * 60,
        4 => 12 * 60
    ];

    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => self::DEFAULT_GROUP,
    ];

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

    public function isArchived(): bool{
        if($this->getOriginal('status_id') == RequestStatus::CLOSED){
            $archivalDate = $this->closed_at->addDays(self::ARCHIVE_AFTER_DAYS);
            if(isset($this->closed_at) && Carbon::now()->greaterThan($archivalDate)){
                return true;
            }
        }
        return $this->getOriginal('status_id') == RequestStatus::CANCELLED;
    }
}
