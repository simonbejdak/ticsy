<?php

namespace App\Helpers\Fields;

use Illuminate\Database\Eloquent\Collection;

class Select extends Field
{
    public array $options;
    public string $value;
    public bool $blank = false;

    function options(array|Collection $options): self
    {
        $this->options = toIterable($options);
        return $this;
    }

    function blank(): self
    {
        $this->blank = true;
        return $this;
    }
}
