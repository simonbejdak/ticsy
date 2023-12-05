<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Services\SlaService;
use Exception;
use Illuminate\Support\Carbon;

class TicketObserver
{
    public function creating(Ticket $ticket): void
    {
        if ($ticket->category->hasItem($ticket->item)){
            throw new Exception('Item cannot be assigned to Ticket if it does not match Category');
        }

        if(!$ticket->isStatusOnHold() && $ticket->on_hold_reason_id !== null){
            throw new Exception('On hold reason cannot be assigned to Ticket if Status is not than on hold');
        }

        if($ticket->isStatusOnHold() && $ticket->onHoldReason === null){
            throw new Exception('On hold reason must be assigned to Ticket if Status is on hold');
        }

        if($ticket->isStatusResolved()){
            $ticket->resolved_at = Carbon::now();
        }
    }

    public function created(Ticket $ticket): void
    {
        SlaService::createSla($ticket);
    }

    public function updating(Ticket $ticket): void{
        if($ticket->isArchived()){
            throw new Exception('Ticket state cannot be changed if Ticket is archived');
        }

        if($ticket->isDirty('status_id')){
            if(!$ticket->isStatusOnHold()){
                $ticket->on_hold_reason_id = null;
            }
            if($ticket->isStatusResolved()){
                $ticket->resolved_at = Carbon::now();
            } else {
                $ticket->resolved_at = null;
            }
        }

        if(!$ticket->isStatusOnHold() && $ticket->on_hold_reason_id !== null){
            throw new Exception('On hold reason cannot be assigned to Ticket if Status is not on hold');
        }
    }

    public function updated(Ticket $ticket): void
    {
        //
    }

    public function saved(Ticket $ticket): void
    {
        if($ticket->isDirty('priority')){
            SlaService::closeSla($ticket->sla());
            SlaService::createSla($ticket);
        }
    }

    public function deleted(Ticket $ticket): void
    {
        //
    }

    public function restored(Ticket $ticket): void
    {
        //
    }

    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
