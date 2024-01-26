<?php

namespace App\Traits;

use App\Helpers\Fields\Fields;

trait HasFields
{
    abstract function fields(): Fields;

    function isFieldDisabled(string $name): bool
    {
        return false;
    }
}
