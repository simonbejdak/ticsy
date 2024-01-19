<?php

namespace App\Helpers\Fields;

class TextInput extends Field
{
    public string $placeholder;
    public string $anchor;

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
