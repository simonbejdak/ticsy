<?php

namespace App\Helpers\Fields;

use App\Enums\FieldPosition;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Fields implements IteratorAggregate, Countable
{
    public array $fields;

    function __construct(Field|callable ...$objects)
    {
        foreach ($objects as $object){
            if($object instanceof Field){
                $this->fields[] = $object;
            } elseif(call_user_func($object) instanceof Field) {
                $this->fields[] = call_user_func($object);
            } elseif(call_user_func($object) instanceof Fields){
                foreach (call_user_func($object) as $field){
                    $this->fields[] = $field;
                }
            }
        }
    }

    function add(Field $field): self
    {
        $this->fields[] = $field;
        return $this;
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

    public function count(): int
    {
        return count($this->fields);
    }
}
