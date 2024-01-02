<?php

namespace App\Interfaces;

use App\Helpers\Fields;

interface Viewable
{
    function fields(): Fields;
}
