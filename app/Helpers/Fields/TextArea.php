<?php

namespace App\Helpers\Fields;

class TextArea extends Field
{
    public string $placeholder;

    static function make(string $name): static
    {
        $static = parent::make($name);
        $static->height = '';
        $static->placeholder = '';

        return $static;
    }

    function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    function style(): string
    {
        return parent::style() .
            'resize-none pt-0.5';
    }
}
