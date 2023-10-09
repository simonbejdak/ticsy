<?php

namespace App\Models;

class TicketConfiguration
{
    const TYPES = [
        'incident' => 1,
        'request' => 2,
        'change' => 3,
    ];
    const DEFAULT_TYPE = 1;
    const CATEGORIES = [
        'network' => 1,
        'server' => 2,
        'computer' => 3,
        'application' => 4,
        'email' => 5,
    ];
    const PRIORITIES = [1, 2, 3, 4];
    const DEFAULT_PRIORITY = 4;
    const DEFAULT_PAGINATION = 10;
    const MINIMUM_DESCRIPTION_CHARACTERS = 8;
    const MAXIMUM_DESCRIPTION_CHARACTERS = 255;
}
