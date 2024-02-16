<?php

namespace App\Helpers\Table;

use App\Enums\SortOrder;

class TableBuilder
{
    protected Table $table;

    function __construct(Table $table){
        $this->table = $table;
    }

    function column(string $title, string $property, array $route = null): self
    {
        $this->table->columns[$title] = [
            'property' => $property,
            'route' => $route,
        ];
        return $this;
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

    function withoutPagination(): self
    {
        $this->table->paginate = false;
        return $this;
    }

    function withoutColumnSearch(): self
    {
        $this->table->columnTextSearch = false;
        return $this;
    }

    function create(): Table
    {
        return $this->table->create();
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
