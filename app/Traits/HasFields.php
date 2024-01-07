<?php

namespace App\Traits;

use App\Helpers\Fields\Fields;
use Symfony\Component\HttpFoundation\Response;

trait HasFields
{
    abstract function fields(): Fields;

    function isFieldDisabled(string $name): bool
    {
        return false;
    }
}
