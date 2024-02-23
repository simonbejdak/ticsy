<?php

namespace App\Helpers\Columns;

class ColumnRoute
{
    public string $name;
    public array $arguments;

    protected function __construct(){}

    static function create(string $name, array $arguments = null): self
    {
        $self = new self();
        $self->name = $name;
        if(isset($arguments)){
            $self->arguments = $arguments;
        }

        return $self;
    }
}
