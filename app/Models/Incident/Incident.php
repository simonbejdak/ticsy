<?php

namespace App\Models\Incident;

use App\Interfaces\Activitable;
use App\Interfaces\Fieldable;
use App\Interfaces\Slable;
use App\Interfaces\Ticket;
use App\Traits\HasSla;
use App\Traits\TicketTrait;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model implements Ticket, Slable, Fieldable, Activitable
{
    use HasSla, HasFactory, TicketTrait;

    protected $guarded = [];
    protected $casts = [
        'resolved_at' => 'datetime',
    ];
    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => self::DEFAULT_PRIORITY,
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::observe(TicketObserver::class);
    }

    const PRIORITY_TO_SLA_MINUTES = [
        1 => 15,
        2 => 60,
        3 => 6 * 60,
        4 => 12 * 60,
    ];

    function category(): BelongsTo
    {
        return $this->belongsTo(IncidentCategory::class, 'category_id');
    }
    function item(): BelongsTo
    {
        return $this->belongsTo(IncidentItem::class, 'item_id');
    }

    function calculateSlaMinutes(): int
    {
        return self::PRIORITY_TO_SLA_MINUTES[$this->priority];
    }

}
