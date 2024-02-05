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

    function style(): string
    {
        return parent::style() .
            ($this->hasAnchor() ? 'hover:cursor-pointer hover:border-gray-400 transform ease-in duration-150 ' : '') .
            ($this->disabled && !$this->hasAnchor() ? 'pointer-events-none ' : 'caret-gray-200 ');
    }
}
