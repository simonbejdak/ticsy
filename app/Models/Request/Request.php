<?php

namespace App\Models\Request;

use App\Models\Group;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Incident\IncidentOnHoldReason;
use App\Models\Incident\IncidentStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;

class Request extends Ticket
{
    use HasFactory;

    public function defineCategoryClass(): string
    {
        return RequestCategory::class;
    }
    public function defineItemClass(): string
    {
        return RequestItem::class;
    }

    public function defineOnHoldReasonClass(): string
    {
        return RequestOnHoldReason::class;
    }
}
