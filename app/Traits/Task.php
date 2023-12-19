<?php

namespace App\Traits;

use App\Models\Group;
use App\Models\Status;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Task
{
    use HasRelationships;

    const DEFAULT_PRIORITY = 4;
    const DEFAULT_STATUS = Status::OPEN;
    const DEFAULT_GROUP = Group::SERVICE_DESK;

    protected $casts = [
        'resolved_at' => 'datetime',
    ];
    protected $attributes = [
        'status_id' => self::DEFAULT_STATUS,
        'group_id' => self::DEFAULT_GROUP,
        'priority' => self::DEFAULT_PRIORITY,
    ];

    function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    function onHoldReason(): BelongsTo
    {
        return $this->belongsTo($this->defineOnHoldReasonClass(), 'on_hold_reason_id');
    }

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    abstract function defineOnHoldReasonClass();
}
