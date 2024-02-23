<?php

namespace App\Models;

use App\Enums\OnHoldReason;
use App\Enums\Priority;
use App\Enums\Status;
use App\Enums\TaskSequence;
use App\Helpers\Strategies\RequestStrategy;
use App\Helpers\Strategies\TaskableStrategy;
use App\Interfaces\SLAble;
use App\Interfaces\Taskable;
use App\Interfaces\Ticket;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Traits\TicketTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;

class Request extends Model implements Ticket, SLAble, Taskable
{
    use TicketTrait, HasFactory;

    protected $guarded = [];
    protected $casts = [
        'status' => Status::class,
        'on_hold_reason' => OnHoldReason::class,
        'priority' => Priority::class,
        'resolved_at' => 'datetime',
        'task_sequence' => TaskSequence::class,
    ];
    protected $attributes = [
        'status' => self::DEFAULT_STATUS,
        'priority' => self::DEFAULT_PRIORITY,
        'group_id' => self::DEFAULT_GROUP,
    ];

    const PRIORITY_TO_SLA_MINUTES = [
        Priority::ONE->value => 30,
        Priority::TWO->value => 2 * 60,
        Priority::THREE->value => 12 * 60,
        Priority::FOUR->value => 24 * 60,
    ];

    const CATEGORY_TO_ITEM = [
        [RequestCategory::COMPUTER, RequestItem::BACKUP],
        [RequestCategory::COMPUTER, RequestItem::CONFIGURE],
        [RequestCategory::SERVER, RequestItem::ACCESS],
        [RequestCategory::SERVER, RequestItem::MAINTENANCE],
        [RequestCategory::SERVER, RequestItem::CONFIGURE],
    ];

    function category(): BelongsTo
    {
        return $this->belongsTo(RequestCategory::class, 'category_id');
    }

    function item(): BelongsTo
    {
        return $this->belongsTo(RequestItem::class, 'item_id');
    }

    function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    function hasNonStartedTask(): bool
    {
        return count(($this->tasks()->notStarted()->get())) > 0;
    }

    function hasAllTasksClosed(): bool
    {
        return count($this->tasks()->notClosed()->get()) == 0;
    }

    public function strategy(): TaskableStrategy
    {
        return RequestStrategy::create($this);
    }

    public function editRoute(): string
    {
        return route('requests.edit', $this);
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
