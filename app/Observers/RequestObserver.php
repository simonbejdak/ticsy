<?php

namespace App\Observers;

use App\Models\Request;
use App\Services\SlaService;
use Exception;

class RequestObserver
{
    function created(Request $request): void
    {
        SlaService::createSla($request);
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
}
