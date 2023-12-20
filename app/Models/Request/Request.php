<?php

namespace App\Models\Request;

use App\Enums\TaskSequence;
use App\Interfaces\Activitable;
use App\Interfaces\Fieldable;
use App\Interfaces\Slable;
use App\Interfaces\Ticket;
use App\Models\Task;
use App\Traits\HasSla;
use App\Traits\TicketTrait;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Request extends Model implements Ticket, Slable, Fieldable, Activitable
{
    use HasSla, HasFactory, TicketTrait;

    protected $guarded = [];
    protected $casts = [
        'resolved_at' => 'datetime',
        'task_sequence' => TaskSequence::class,
    ];
    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => self::DEFAULT_PRIORITY,
        'task_sequence' => self::DEFAULT_TASK_SEQUENCE,
    ];

    const DEFAULT_TASK_SEQUENCE = TaskSequence::GRADUAL;
    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::observe(TicketObserver::class);
    }

    function category(): BelongsTo
    {
        return $this->belongsTo(RequestCategory::class, 'category_id');
    }

    function item(): BelongsTo
    {
        return $this->belongsTo(RequestItem::class, 'item_id');
    }

    function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

}
