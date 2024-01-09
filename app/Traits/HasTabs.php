<?php

namespace App\Traits;

use App\Enums\Tab;
use App\Livewire\TempTabs;
use InvalidArgumentException;

trait HasTabs
{
    abstract function tabs(): array;
}
