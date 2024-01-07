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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->tabs);
    }
}
