<?php

namespace App\Models;

use App\Enums\TaskSequence;
use App\Interfaces\Activitable;
use App\Interfaces\Fieldable;
use App\Interfaces\Slable;
use App\Interfaces\Ticket;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Traits\HasSla;
use App\Traits\TicketTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Task extends Model implements Ticket, Slable, Fieldable, Activitable
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

    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    function category(): HasOneThrough
    {
        return $this->hasOneThrough(
            RequestCategory::class,
            Request::class,
            'category_id',
            'id',
            'request_id',
            'id'
        );
    }

    function item(): HasOneThrough
    {
        return $this->hasOneThrough(
            RequestItem::class,
            Request::class,
            'item_id',
            'id',
            'request_id',
            'id'
        );
    }

    function scopeNotStarted(Builder $query): void
    {
        $query->where('started_at', '=', null);
    }

    public function isArchived(): bool{
        return
            $this->getOriginal('status_id') == Status::RESOLVED ||
            $this->getOriginal('status_id') == Status::CANCELLED
        ;
    }

}
