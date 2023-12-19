<?php

namespace App\Models\Incident;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incident extends Ticket
{
    use HasFactory;

    const PRIORITY_TO_SLA_MINUTES = [
        1 => 30,
        2 => 2 * 60,
        3 => 12 * 60,
        4 => 24 * 60,
    ];

    public function defineCategoryClass(): string
    {
        return IncidentCategory::class;
    }
    public function defineItemClass(): string
    {
        return IncidentItem::class;
    }

    public function defineOnHoldReasonClass(): string
    {
        return IncidentOnHoldReason::class;
    }
}
