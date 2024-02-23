<?php

namespace App\Helpers\Columns;

use App\Enums\FieldPosition;
use App\Helpers\Fields\Field;
use App\Helpers\Fields\Fields;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Columns implements IteratorAggregate
{
    public array $columns;

    protected function __construct(){}

    static function create(Column|callable ...$objects): self
    {
        $self = new self;
        foreach ($objects as $object){
            if($object instanceof Column){
                $self->columns[] = $object;
            } elseif(call_user_func($object) instanceof Columns){
                foreach (call_user_func($object) as $column){
                    $self->columns[] = $column;
                }
            }
        }

        return $self;
    }

    function add(Column $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    function visible(): self
    {
        foreach($this->columns as $column){
            if(!$column->visible){
                unset($this->columns[array_search($column, $this->columns)]);
            }
        }
        return $this;
    }

    function configuration(string $configuration): self
    {
        $configuration = explode(',', $configuration);
        foreach ($this->columns as $column){
            if(!in_array($column->header, $configuration)){
                $column->hidden();
            } else {
                $this->moveColumn($column, $configuration);
            }
        }
        return $this;
    }

    function getIterator(): Traversable
    {
        return new ArrayIterator($this->columns);
    }

    // Function to reposition column based on provided configuration array
    protected function moveColumn(Column $column, array $configuration): void
    {
        moveElement($this->columns, array_search($column, $this->columns), array_search($column->header, $configuration));
    }
}
