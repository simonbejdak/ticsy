<?php

namespace App\Models;

use App\Enums\OnHoldReason;
use App\Enums\Priority;
use App\Enums\Status;
use App\Helpers\Strategies\IncidentStrategy;
use App\Helpers\Strategies\TicketStrategy;
use App\Interfaces\SLAble;
use App\Interfaces\Ticket;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Traits\TicketTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;

class Incident extends Model implements Ticket, SLAble
{
    use TicketTrait, HasFactory;

    protected $guarded = [];
    protected $casts = [
        'status' => Status::class,
        'on_hold_reason' => OnHoldReason::class,
        'priority' => Priority::class,
        'resolved_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::DEFAULT_STATUS,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => self::DEFAULT_GROUP,
    ];

    const PRIORITY_TO_SLA_MINUTES = [
        Priority::ONE->value => 15,
        Priority::TWO->value => 60,
        Priority::THREE->value => 6 * 60,
        Priority::FOUR->value => 12 * 60,
    ];

    const CATEGORY_TO_ITEM = [
        [IncidentCategory::NETWORK, IncidentItem::ISSUE],
        [IncidentCategory::NETWORK, IncidentItem::FAILED_NODE],

        [IncidentCategory::SERVER, IncidentItem::ISSUE],
        [IncidentCategory::SERVER, IncidentItem::BACKUP],
        [IncidentCategory::SERVER, IncidentItem::FAILURE],

        [IncidentCategory::COMPUTER, IncidentItem::ISSUE],
        [IncidentCategory::COMPUTER, IncidentItem::COMPUTER_IS_TOO_SLOW],
        [IncidentCategory::COMPUTER, IncidentItem::APPLICATION_ERROR],
        [IncidentCategory::COMPUTER, IncidentItem::FAILURE],

        [IncidentCategory::APPLICATION, IncidentItem::ISSUE],
        [IncidentCategory::APPLICATION, IncidentItem::APPLICATION_ERROR],

        [IncidentCategory::EMAIL, IncidentItem::ISSUE],
        [IncidentCategory::EMAIL, IncidentItem::BACKUP],
    ];

    function category(): BelongsTo
    {
        return $this->belongsTo(IncidentCategory::class, 'category_id');
    }

    function item(): BelongsTo
    {
        return $this->belongsTo(IncidentItem::class, 'item_id');
    }

    function strategy(): TicketStrategy
    {
        return IncidentStrategy::create($this);
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
