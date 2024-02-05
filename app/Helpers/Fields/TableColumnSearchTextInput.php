<?php

namespace App\Helpers\Fields;

class TableColumnSearchTextInput extends TextInput
{
    static function make(string $name = null): static
    {
        $static = parent::make('tableSearch');
        $static->withoutLabel();
        $static->placeholder('Search');
        $static->width = 'w-full';

        return $static;
    }

    function property(string $property): self
    {
        $this->wireModel('searchCases.' . $property);
        return $this;
    }
}
