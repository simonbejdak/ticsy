<?php

namespace App\Helpers\Fields;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Fields implements IteratorAggregate
{
    public array $fields;

    function __construct(Field ...$fields)
    {
        foreach ($fields as $field){
            $this->fields[] = $field;
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->fields);
    }
}
