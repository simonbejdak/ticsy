<?php

namespace App\Helpers\Table;

use App\Enums\SortOrder;

class TableBuilder
{
    protected Table $table;

    function __construct(Table $table){
        $this->table = $table;
    }

    function column(string $title, string $propertyPath, array $route = null): self
    {
        $this->table->columns[$title] = [
            'propertyPath' => $propertyPath,
            'route' => $route,
        ];
        return $this;
    }

    function paginate(int $number): self
    {
        $this->table->pagination = $number;
        return $this;
    }

    function paginationIndex(int $paginationIndex): self
    {
        $this->table->paginationIndex = $paginationIndex;
        return $this;
    }

    function sortByColumn(string $column): self
    {
        $this->table->sortByColumn = $column;
        return $this;
    }

    function sortOrder(SortOrder $sortOrder): self
    {
        $this->table->sortOrder = $sortOrder;
        return $this;
    }

    function get(): Table
    {
        return $this->table->create();
    }
}
