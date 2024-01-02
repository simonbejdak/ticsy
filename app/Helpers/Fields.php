<?php

namespace App\Helpers;

use App\Enums\FieldPosition;
use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

class Fields implements IteratorAggregate
{
    protected array $fields;

    function __construct(...$fields)
    {
        foreach ($fields as $field) {
            if($field instanceof Field){
                $this->fields[] = $field;
            } else {
                throw new InvalidArgumentException('Fields constructor accepts only instances of type Field');
            }
        }
    }

    function inGrid(): self
    {
        $self = new self;
        foreach ($this as $field){
            if($field->position == FieldPosition::IN_GRID){
                $self->add($field);
            }
        }

        return $self;
    }

    function outsideGrid(): self
    {
        $self = new self;
        foreach ($this as $field){
            if($field->position == FieldPosition::OUTSIDE_GRID){
                $self->add($field);
            }
        }

        return $self;
    }

    function add(Field $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    function getIterator(): Traversable
    {
        return new ArrayIterator($this->fields);
    }
}
