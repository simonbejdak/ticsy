<?php

namespace App\Helpers\Fields;

class TextInput extends Field
{
    public string $placeholder;

    protected function __construct()
    {
        parent::__construct();
        $this->placeholder = '';
    }

    function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }
}
