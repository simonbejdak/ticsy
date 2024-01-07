<?php

namespace App\Helpers\Fields;

use App\Enums\FieldPosition;
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

    function insideGrid(): self
    {
        foreach($this->fields as $field){
            if($field->position !== FieldPosition::INSIDE_GRID){
                unset($this->fields[array_search($field, $this->fields)]);
            }
        }
        return $this;
    }

    function outsideGrid(): self
    {
        foreach($this->fields as $field){
            if($field->position !== FieldPosition::OUTSIDE_GRID){
                unset($this->fields[array_search($field, $this->fields)]);
            }
        }
        return $this;
    }

    function getIterator(): Traversable
    {
        return new ArrayIterator($this->fields);
    }
}
