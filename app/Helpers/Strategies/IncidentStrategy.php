<?php

namespace App\Helpers\Strategies;

use App\Models\Incident;

class IncidentStrategy extends TicketStrategy
{
    static function create(Incident $incident): self
    {
        return new static();
    }
}
