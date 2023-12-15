<?php

namespace App\Interfaces;

interface Fieldable
{
    public function isFieldModifiable(string $name): bool;
}
