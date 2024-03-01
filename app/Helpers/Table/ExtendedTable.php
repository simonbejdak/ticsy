<?php

namespace App\Helpers\Table;

use App\Enums\SortOrder;
use App\Helpers\Columns\Column;
use App\Helpers\Columns\Columns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ExtendedTable extends Table
{
    public array $searchCases;
    public int $itemsPerPage;
    public int $paginationIndex;
    public Collection $paginatedCollection;

    static function make(Builder $builder): TableBuilder
    {
        $static = new static();
        $static->columns = Columns::create();
        $static->searchCases = [];
        $static->paginationIndex = 1;
        $static->builder = $builder;
        $static->sortOrder = SortOrder::ASCENDING;
        return new ExtendedTableBuilder($static);
    }

    function hasPreviousPage(): bool
    {
        return $this->paginationIndex > 1;
    }

    function hasNextPage(): int
    {
        return $this->paginationIndex < ($this->count - $this->itemsPerPage);
    }

    function to(): int
    {
        return $this->paginationIndex + $this->itemsPerPage;
    }

    protected function paginateCollection(): void
    {
        $this->collection = $this->collection->skip($this->paginationIndex - 1)->take($this->itemsPerPage);
    }

    function create(): self
    {
        parent::create();
        foreach ($this->searchCases as $property => $value){
            $this->collection = $this->collection->filter(function ($model) use ($property, $value){
                return str_contains($this->data_get($model, $property), $value);
            });
        }
        $this->paginateCollection();
        return $this;
    }
}
