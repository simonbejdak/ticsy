<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketConfig;
use Exception;
use Illuminate\Support\Carbon;

class TicketObserver
{
    public function creating(Ticket $ticket): void
    {
        $category = $ticket->category;
        $categoryItems = $category->items()->where('id', '=', $ticket->item->id)->get();
        if(count($categoryItems) == 0){
            throw new Exception('Item cannot be assigned to Ticket if it does not match Category');
        }

        if($ticket->on_hold_reason_id !== null && !$ticket->isStatus('on_hold')){
            throw new Exception('Status on hold reason cannot be assigned to Ticket if Status is different than on hold');
        }

        if($ticket->isStatus('on_hold') && $ticket->onHoldReason === null){
            throw new Exception('Status on hold reason must be assigned to Ticket if Status is on hold');
        }

        if($ticket->isResolved()){
            $ticket->resolved_at = Carbon::now();
        }
    }

    public function created(Ticket $ticket): void
    {
        //
    }

    public function updating(Ticket $ticket): void{
        if($ticket->isArchived()){
            throw new Exception('Ticket state cannot be changed if Ticket is archived');
        }
        if($ticket->isDirty('status_id') && $ticket->isStatus('resolved')){
            $ticket->resolved_at = Carbon::now();
        }
        if($ticket->isDirty('status_id') && !$ticket->isStatus('resolved')){
            $ticket->resolved_at = null;
        }
        if($ticket->isDirty('status_id') && !$ticket->isStatus('on_hold')){
            $ticket->on_hold_reason_id = null;
        }
        if($ticket->on_hold_reason_id !== null && !$ticket->isStatus('on_hold')){
            throw new Exception('Status on hold reason cannot be assigned to Ticket if Status is different than on hold');
        }
    }

    public function updated(Ticket $ticket): void
    {
        //        
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
