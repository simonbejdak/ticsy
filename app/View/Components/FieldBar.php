<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FieldBar extends Field
{
    public int $percentage;
    public function __construct(string $name, bool|string $permission, int $percentage, string $value)
    {
        parent::__construct($name, null, false, $permission);

        $this->percentage = $percentage;
        $this->value = $value;
    }

    public function render(): View|Closure|string
    {
        return view('components.field-bar');
    }
}
