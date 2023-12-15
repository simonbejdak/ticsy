<?php

namespace App\Observers;

use App\Models\Incident\Incident;
use App\Services\SlaService;
use Exception;
use Illuminate\Support\Carbon;

class IncidentObserver
{
    public function creating(Incident $incident): void
    {
        if ($incident->category->hasItem($incident->item)){
            throw new Exception('IncidentItem cannot be assigned to Incident if it does not match IncidentCategory');
        }

        if(!$incident->isStatus('on_hold') && $incident->on_hold_reason_id !== null){
            throw new Exception('On hold reason cannot be assigned to Incident if IncidentStatus is not than on hold');
        }

        if($incident->isStatus('on_hold') && $incident->on_hold_reason_id === null){
            throw new Exception('On hold reason must be assigned to Incident if IncidentStatus is on hold');
        }

        if($incident->isStatus('resolved')){
            $incident->resolved_at = Carbon::now();
        }
    }

    public function created(Incident $incident): void
    {
        SlaService::createSla($incident);
    }

    public function updating(Incident $incident): void{
        if($incident->isArchived()){
            throw new Exception('Incident state cannot be changed if Incident is archived');
        }

        if($incident->isDirty('status_id')){
            if(!$incident->isStatus('on_hold')){
                $incident->on_hold_reason_id = null;
            }
            if($incident->isStatus('resolved')){
                $incident->resolved_at = Carbon::now();
            } else {
                $incident->resolved_at = null;
            }
        }

        if(!$incident->isStatus('on_hold') && $incident->on_hold_reason_id !== null){
            throw new Exception('On hold reason cannot be assigned to Incident if IncidentStatus is not on hold');
        }
    }

    public function saved(Incident $incident): void
    {
        if($incident->isDirty('priority')){
            SlaService::closeSla($incident->sla);
            SlaService::createSla($incident);
        }
    }
}
