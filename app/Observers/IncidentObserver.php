<?php

namespace App\Observers;

use App\Models\Incident;
use Exception;

class IncidentObserver
{
    function creating(Incident $incident): void
    {
        if ($incident->category->hasItem($incident->item)){
            throw new Exception('Item cannot be assigned to '. get_class_name($incident) .' if it does not match Category');
        }
    }
}
