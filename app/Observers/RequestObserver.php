<?php

namespace App\Observers;

use App\Models\Request\Request;
use App\Services\SlaService;
use Carbon\Carbon;
use Exception;

class RequestObserver
{
    function creating(Request $request): void
    {
        if ($request->category->hasItem($request->item)){
            throw new Exception('IncidentItem cannot be assigned to Request if it does not match IncidentCategory');
        }
        if($request->isStatus('closed')){
            $request->closed_at = Carbon::now();
        }
    }

    function created(Request $request): void
    {
        SlaService::createSla($request);
    }

    function updating(Request $request): void
    {
        if($request->statusChangedTo('closed')){
            $request->closed_at = Carbon::now();
        }
        if($request->statusChangedFrom('closed')){
            $request->closed_at = null;
        }
    }

    function saving(Request $request): void
    {
        if(!$request->isStatus('on_hold') && $request->on_hold_reason_id !== null){
            throw new Exception('On hold reason cannot be assigned to Request if Status is not on hold');
        }
        if($request->isStatus('on_hold') && $request->on_hold_reason_id === null){
            throw new Exception('On hold reason must be assigned to Request if Status is on hold');
        }
    }

    function saved(Request $request): void
    {
        if($request->isDirty('priority')){
            SlaService::closeSla($request->sla);
            SlaService::createSla($request);
        }
    }
}
