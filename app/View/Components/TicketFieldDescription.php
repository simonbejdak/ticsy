<?php

namespace App\View\Components;

class TicketFieldDescription extends TicketField
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'description';
    }
}
