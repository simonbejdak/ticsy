<?php

namespace App\Helpers\Columns;

class Column
{
    public string $header;
    public string $property;
    public ColumnRoute $route;
    public bool $visible;

    protected function __construct(){}

    static function create(string $header, string $property, ColumnRoute $route = null): self
    {
        $self = new self();
        $self->header = $header;
        $self->property = $property;
        $self->visible = true;
        if(isset($route)){
            $self->route = $route;
        }

        return $self;
    }

    function hidden(): self
    {
        $this->visible = false;
        return $this;
    }

    function visible(): self
    {
        $this->visible = true;
        return $this;
    }
}
