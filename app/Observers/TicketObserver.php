<?php

namespace App\Observers;

use App\Services\SlaService;
use App\Interfaces\Ticket;
use Exception;
use Illuminate\Support\Carbon;

class TicketObserver
{
    public function creating(Ticket $ticket): void
    {
        if(!$ticket->isStatus('on_hold') && $ticket->on_hold_reason_id !== null){
            throw new Exception('On hold reason cannot be assigned to TicketTrait if Status is not on hold');
        }

        if($ticket->isStatus('on_hold') && $ticket->on_hold_reason_id === null){
            throw new Exception('On hold reason must be assigned to TicketTrait if Status is on hold');
        }

        if($ticket->isStatus('resolved')){
            $ticket->resolved_at = Carbon::now();
        }
    }

    public function created(Ticket $ticket): void
    {
        SlaService::createSla($ticket);
    }

    public function updating(Ticket $ticket): void{
        if($ticket->isArchived()){
            throw new Exception('TicketTrait state cannot be changed if TicketTrait is archived');
        }

        if($ticket->isDirty('status_id')){
            if(!$ticket->isStatus('on_hold')){
                $ticket->on_hold_reason_id = null;
            }
            if($ticket->isStatus('resolved')){
                $ticket->resolved_at = Carbon::now();
            } else {
                $ticket->resolved_at = null;
            }
        }

        if(!$ticket->isStatus('on_hold') && $ticket->on_hold_reason_id !== null){
            throw new Exception('On hold reason cannot be assigned to TicketTrait if Status is not on hold');
        }
    }

    public function saved(Ticket $ticket): void
    {
        if($ticket->priorityChanged()){
            SlaService::closeSla($ticket->sla);
            SlaService::createSla($ticket);
        }
    }
}
