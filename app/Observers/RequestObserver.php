<?php

namespace App\Observers;

use App\Models\Request;
use Exception;

class RequestObserver
{
    public function creating(Request $request): void
    {
        if ($request->category->hasItem($request->item)){
            throw new Exception('Item cannot be assigned to '. get_class_name($request) .' if it does not match Category');
        }
    }
}
