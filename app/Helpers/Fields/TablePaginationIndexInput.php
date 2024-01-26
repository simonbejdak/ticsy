<?php

namespace App\Helpers\Fields;

class TablePaginationIndexInput extends TextInput
{
    function style(): string
    {
        return parent::style() . ' w-14 mx-2 text-right';
    }
}
