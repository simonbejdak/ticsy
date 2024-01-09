<?php

namespace App\Models;

use App\Enums\Status;
use App\Interfaces\Activitable;
use App\Interfaces\Slable;
use App\Interfaces\Ticket;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Traits\HasSla;
use App\Traits\TicketTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model implements Ticket, Slable, Activitable
{
    use HasSla, HasFactory, TicketTrait;

    protected $guarded = [];
    protected $casts = [
        'status' => Status::class,
        'resolved_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => self::DEFAULT_PRIORITY,
    ];

    const PRIORITY_TO_SLA_MINUTES = [
        1 => 15,
        2 => 60,
        3 => 6 * 60,
        4 => 12 * 60,
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
}
