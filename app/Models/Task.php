<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\Status;
use App\Enums\OnHoldReason;
use App\Interfaces\Activitable;
use App\Interfaces\Slable;
use App\Interfaces\Ticket;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Traits\HasSla;
use App\Traits\TicketTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Task extends Model implements Ticket, Slable, Activitable
{
    use HasSla, HasFactory, TicketTrait;

    protected $guarded = [];
    protected $casts = [
        'status' => Status::class,
        'on_hold_reason' => OnHoldReason::class,
        'priority' => Priority::class,
        'resolved_at' => 'datetime',
    ];
    protected $attributes = [
        'status' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => self::DEFAULT_PRIORITY,
    ];

    const PRIORITY_TO_SLA_MINUTES = [
        Priority::ONE->value => 30,
        Priority::TWO->value => 2 * 60,
        Priority::THREE->value => 12 * 60,
        Priority::FOUR->value => 24 * 60,
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
            'id',
            'id',
            'request_id',
            'category_id',
        );
    }

    function item(): HasOneThrough
    {
        return $this->hasOneThrough(
            RequestItem::class,
            Request::class,
            'id',
            'id',
            'request_id',
            'category_id',
        );
    }

    function scopeNotStarted(Builder $query): void
    {
        $query->where('started_at', '=', null);
    }

    function scopeNotClosed(Builder $query): void
    {
        $query->where('status', '!=', Status::RESOLVED)->where('status', '!=', Status::CANCELLED);
    }

    public function isArchived(): bool{
        return
            $this->getOriginal('status') == Status::RESOLVED ||
            $this->getOriginal('status') == Status::CANCELLED
        ;
    }
}
