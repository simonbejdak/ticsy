<?php

namespace App\Interfaces;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;

interface Ticket
{
    function caller(): BelongsTo;
    function resolver(): BelongsTo;
    function category(): BelongsTo|HasOneThrough;
    function item(): BelongsTo|HasOneThrough;
    function onHoldReason(): BelongsTo;
    function group(): BelongsTo;
    function slas(): MorphMany;
    function isStatus(Status ...$statuses): bool;
    function isArchived(): bool;
    function statusChanged(): bool;
    function statusChangedTo(Status $status): bool;
    function statusChangedFrom(Status $status): bool;
    function priorityChanged(): bool;
    function calculateSlaMinutes(): int;
    function getActivityLogOptions(): LogOptions;
}
