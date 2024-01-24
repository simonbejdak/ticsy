<?php

namespace App\Helpers\Fields;

use Illuminate\Database\Eloquent\Collection;

class Select extends Field
{
    public array $options;
    public bool $blank;

    static function make(string $name): static
    {
        $static = parent::make($name);
        $static->options = [];
        $static->blank = false;

        return $static;
    }

    function options(string|array|Collection $options): self
    {
        $this->options = toIterable($options);
        return $this;
    }

    function blank(): self
    {
        $this->blank = true;
        return $this;
    }

    function isDisabled(): bool
    {
        return $this->disabled || count($this->options) == 0;
    }
}
