<?php

namespace App\Helpers\Table;

use App\Enums\SortOrder;
use App\Helpers\Columns\Column;
use App\Helpers\Columns\Columns;

class ExtendedTableBuilder extends TableBuilder
{
    protected Table $table;

    function __construct(ExtendedTable $table){
        parent::__construct($table);
    }

    function itemsPerPage(int $number): self
    {
        $this->table->itemsPerPage = $number;
        return $this;
    }

    function paginationIndex(int $number): self
    {
        $this->table->paginationIndex = $number;
        return $this;
    }

    function searchCases(array $searchCases): self
    {
        foreach($searchCases as $property => $value){
            if(is_array($value)){
                $value = array_values($value)[0];
            }
            $this->searchCase($property, $value);
        }
        return $this;
    }

    protected function searchCase(string $property, string $value): self
    {
        $this->table->searchCases[$property] = $value;
        return $this;
    }
}
