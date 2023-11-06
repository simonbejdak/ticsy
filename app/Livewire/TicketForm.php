<?php

namespace App\Livewire;

use Livewire\Component;

abstract class TicketForm extends Component
{

    public function updated($property): void
    {
        $this->authorize('set' . ucfirst($property), $this->ticket);
    }
}
