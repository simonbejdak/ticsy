<?php

namespace App\Helpers\Table;

use App\Enums\SortOrder;
use App\Helpers\Columns\Column;
use App\Helpers\Columns\Columns;

class TableBuilder
{
    protected Table $table;

    function __construct(Table $table){
        $this->table = $table;
    }

    function column(Column $column): self
    {
        $this->table->columns->add($column);
        return $this;
    }

    function columns(Columns $columns): self
    {
        foreach($columns as $column){
            if($column->visible){
                $this->column($column);
            }
        }
        return $this;
    }

    function sortProperty(string $property): self
    {
        $this->table->sortProperty = $property;
        return $this;
    }

    function sortOrder(SortOrder $sortOrder): self
    {
        $this->table->sortOrder = $sortOrder;
        return $this;
    }

    function create(): Table
    {
        return $this->table->create();
    }
}
