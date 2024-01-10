<?php

namespace App\Observers;

use App\Enums\Status;
use App\Services\SlaService;
use Exception;
use Illuminate\Support\Carbon;

class TicketObserver
{
    public function creating($ticket): void
    {
        if(!$ticket->isStatus(Status::ON_HOLD) && $ticket->on_hold_reason !== null){
            throw new Exception('On hold reason cannot be assigned to '. get_class_name($ticket) .' if Status is not on hold');
        }

        if($ticket->isStatus(Status::ON_HOLD) && $ticket->on_hold_reason === null){
            throw new Exception('On hold reason must be assigned to '. get_class_name($ticket) .' if Status is on hold');
        }

        if($ticket->isStatus(Status::RESOLVED)){
            $ticket->resolved_at = Carbon::now();
        }
    }

    public function created($ticket): void
    {
        SlaService::createSla($ticket);
    }

    public function updating($ticket): void{
        if($ticket->isArchived()){
            throw new Exception(get_class_name($ticket) .' state cannot be changed if '. get_class_name($ticket) .' is archived');
        }

        if($ticket->isDirty('status')){
            if(!$ticket->isStatus(Status::ON_HOLD)){
                $ticket->on_hold_reason = null;
            }
            if($ticket->isStatus(Status::RESOLVED)){
                $ticket->resolved_at = Carbon::now();
            } else {
                $ticket->resolved_at = null;
            }
        }

        if(!$ticket->isStatus(Status::ON_HOLD) && $ticket->on_hold_reason !== null){
            throw new Exception('On hold reason cannot be assigned to '. get_class_name($ticket) .' if Status is not on hold');
        }
    }

    public function saved($ticket): void
    {
        if($ticket->priorityChanged()){
            SlaService::closeSla($ticket->sla);
            SlaService::createSla($ticket);
        }
    }
}
