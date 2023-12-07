<?php

namespace App\Helpers;

interface Fieldable
{
    public function isFieldModifiable(string $name): bool;
}
