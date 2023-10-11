<?php

namespace App\Livewire;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class TicketFieldStatus extends Component
{
    public Ticket $ticket;
    public string $name;
    public string $selected;
    public int $status;
    public Collection $statuses;
    public bool $required;
    public bool $disabled;

    public function mount()
    {
        $this->name = 'status';
        $this->selected = $this->ticket->status->name;
        $this->statuses = Status::all();
        $this->required = true;
        $this->disabled = $this->isDisabled();
    }

    public function render()
    {
        return view('livewire.ticket-field-status');
    }

    private function isDisabled()
    {
        if(auth()->user()->cannot('setStatus', $this->ticket)){
            return true;
        }
        if($this->ticket->status->id === TicketConfiguration::STATUSES['resolved']){
            return true;
        };

        return false;
    }
}
