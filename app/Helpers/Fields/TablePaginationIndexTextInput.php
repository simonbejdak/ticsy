<?php

namespace App\Helpers\Fields;

class TablePaginationIndexTextInput extends TextInput
{
    static function make(string $name = null): static
    {
        $static = parent::make('paginationIndex');
        $static->withoutLabel();
        $static->width = 'w-14';

        return $static;
    }

    function style(): string
    {
        return parent::style() . ' mx-2 text-right';
    }
}
