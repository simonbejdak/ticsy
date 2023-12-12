<?php

namespace App\Observers;

use App\Models\Request;
use App\Services\SlaService;

class RequestObserver
{
    public function created(Request $request): void
    {
        SlaService::createSla($request);
    }
}
