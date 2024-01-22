<?php

namespace App\Helpers\Fields;

use Illuminate\Database\Eloquent\Collection;

class Select extends Field
{
    public array $options;
    public bool $blank;

    protected function __construct()
    {
        parent::__construct();
        $this->options = [];
        $this->blank = false;
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
