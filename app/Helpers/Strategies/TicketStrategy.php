<?php

namespace App\Helpers\Strategies;

use App\Models\Group;

abstract class TicketStrategy
{
    public Group|null $group = null;
}
