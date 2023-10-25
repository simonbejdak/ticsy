<?php

namespace App\View\Components;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use function PHPUnit\Framework\isInstanceOf;

class TicketFieldSelectStatus extends TicketFieldSelect
{
    public function __construct(Ticket $ticket){
        parent::__construct();

        $this->ticket = $ticket;
        $this->name = 'status';
        $this->options = $this->toIterable(Status::all());
        $this->required = true;
        $this->disabled = $this->isDisabled();
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-select');
    }

    public function isDisabled(): bool
    {
        if(auth()->user()->cannot('setStatus', $this->ticket)){
            return true;
        }
        if($this->ticket->isArchived()){
            return true;
        };

        return false;
    }
}
