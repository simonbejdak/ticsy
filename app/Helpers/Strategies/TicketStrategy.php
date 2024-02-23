<?php

namespace App\Helpers\Strategies;

use App\Models\Group;

abstract class TicketStrategy
{
    public Group $group;

    protected function __construct(){
        $this->group = Group::getServiceDeskGroup();
    }
}
