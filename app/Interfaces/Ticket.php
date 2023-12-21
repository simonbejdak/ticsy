<?php

namespace App\Interfaces;

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
    function status(): BelongsTo;
    function onHoldReason(): BelongsTo;
    function group(): BelongsTo;
    function slas(): MorphMany;
    function isStatus(...$statuses): bool;
    function isArchived(): bool;
    function statusChanged(): bool;
    function statusChangedTo(string $status): bool;
    function statusChangedFrom(string $status): bool;
    function priorityChanged(): bool;
    function isFieldModifiable(string $name): bool;
    function getActivityLogOptions(): LogOptions;
}
