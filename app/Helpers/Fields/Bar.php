<?php

namespace App\Helpers\Fields;

class Bar extends Field
{
    public int $percentage;

    function percentage(int $percentage): self
    {
        $this->percentage = $percentage;
        return $this;
    }
}
