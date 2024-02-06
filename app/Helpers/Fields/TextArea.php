<?php

namespace App\Helpers\Fields;

class TextArea extends Field
{
    public string $placeholder;

    static function make(string $name): static
    {
        $static = parent::make($name);
        $static->placeholder = '';

        return $static;
    }

    function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }
}
