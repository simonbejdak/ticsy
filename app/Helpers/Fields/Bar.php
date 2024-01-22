<?php

namespace App\Helpers\Fields;

class Bar extends Field
{
    public int $percentage;
    protected bool $pulse;

    function percentage(int $percentage): self
    {
        $this->percentage = $percentage;
        $this->pulse = false;
        return $this;
    }

    function pulse(): self
    {
        $this->pulse = true;
        return $this;
    }

    function isPulse(): bool
    {
        return $this->pulse;
    }
}
