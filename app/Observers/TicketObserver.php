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
        if(count($ticket->category->items()->where('id', '=', $ticket->item->id)->get()) === 0){
            throw new Exception('Item cannot be assigned to Ticket if it does not match Category');
        }

        if($ticket->status_id == TicketConfig::STATUSES['resolved']){
            $ticket->resolved_at = Carbon::now();
        }
    }

    public function created(Ticket $ticket): void
    {
        //
    }

    public function updating(Ticket $ticket): void{
        if($ticket->isDirty('status_id') && $ticket->isStatus('resolved')){
            $ticket->resolved_at = Carbon::now();
        }
        if($ticket->isDirty('status_id') && !$ticket->isStatus('resolved')){
            $ticket->resolved_at = null;
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
