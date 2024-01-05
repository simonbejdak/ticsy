<?php

namespace App\Helpers;

use App\Enums\Tab;

class Tabs
{
    public array $tabs;

    function setTabs(Tab ...$tabs): void
    {
        foreach ($tabs as $tab){
            $this->tabs[] = $tab;
        }
    }

    function getTabs(): array
    {
        return $this->tabs;
    }
}
