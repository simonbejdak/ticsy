<?php

namespace App\Helpers\Fields;

class TableColumnSearchTextInput extends TextInput
{
    static function make(string $name = null): static
    {
        $static = parent::make('tableSearch');
        $static->withoutLabel();
        $static->placeholder('Search');

        return $static;
    }

    function propertyPath(string $propertyPath): self
    {
        $this->wireModel('searchCases.' . $propertyPath);
        return $this;
    }
}
