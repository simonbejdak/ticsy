<?php

namespace App\Traits;

use App\Enums\Tab;
use App\Livewire\Tabs;
use InvalidArgumentException;

trait HasTabs
{
    public array $validTabs = ['activities', 'tasks'];

    abstract function tabs(Tab ...$tabs): Tabs;

    function bootHasTabs(): void
    {
        $this->checkIfTabsAreValid();
    }

    protected function checkIfTabsAreValid(): void
    {
        foreach ($this->tabs as $tab){
            if(!in_array($tab, $this->validTabs)){
                throw new InvalidArgumentException('The ' . $tab . ' does not exist in ticketing system.');
            }
        }
    }
}
