<?php

namespace App\Helpers\Fields;

class TablePaginationIndexInput extends TextInput
{
    static function make(string $name): static
    {
        $static = parent::make($name);
        $static->width = 'w-14';
        $static->placeholder = '';

        return $static;
    }

    function style(): string
    {
        return parent::style() . ' mx-2 text-right';
    }
}
