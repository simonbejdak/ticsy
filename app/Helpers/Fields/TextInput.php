<?php

namespace App\Helpers\Fields;

class TextInput extends Field
{
    public string $placeholder;
    public string $anchor;

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

    function anchor(string $anchor): self
    {
        $this->anchor = $anchor;
        return $this;
    }

    function hasAnchor(): bool
    {
        return isset($this->anchor);
    }
}
