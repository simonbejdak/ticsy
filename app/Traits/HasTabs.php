<?php

namespace App\Traits;

use InvalidArgumentException;

trait HasTabs
{
    public array $validTabs = ['activities', 'tasks'];

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
