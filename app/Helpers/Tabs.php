<?php

namespace App\Helpers;

use App\Enums\Tab;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Tabs implements IteratorAggregate
{
    public array $tabs;

    function __construct(Tab ...$tabs)
    {
        foreach ($tabs as $tab){
            $this->tabs[] = $tab;
        }
    }

    function first(): Tab
    {
        return $this->tabs[0];
    }

    function tabKey(Tab $tab): int
    {
        return array_search($tab, $this->tabs);
    }

    function has(Tab $tab): bool
    {
        return in_array($tab, $this->tabs);
    }

    function getIterator(): Traversable
    {
        return new ArrayIterator($this->tabs);
    }
}
