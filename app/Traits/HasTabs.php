<?php

namespace App\Traits;

use App\Livewire\TempTabs;

trait HasTabs
{
    abstract function tabs(): array;
}
