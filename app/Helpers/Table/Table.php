<?php

namespace App\Helpers\Table;

use App\Enums\SortOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class Table
{
    const DEFAULT_PAGINATION = 25;

    public Builder $builder;
    public array $columns;
    public array $searchCases;
    public bool $paginate;
    public bool $columnSearch;
    public int $pagination;
    public int $paginationIndex;
    public int $modelCount;
    public string $sortByColumn;
    public SortOrder $sortOrder;
    public Collection $models;
    public Collection $paginatedModels;

    protected function __construct(){}

    static function make(Builder $builder): TableBuilder
    {
        $static = new static();
        $static->searchCases = [];
        $static->paginate = true;
        $static->columnSearch = true;
        $static->pagination = $static::DEFAULT_PAGINATION;
        $static->paginationIndex = 1;
        $static->builder = $builder;
        $static->sortOrder = SortOrder::ASCENDING;
        return new TableBuilder($static);
    }

    function isPreviousPage(): bool
    {
        return $this->paginationIndex > 1;
    }

    function isNextPage(): int
    {
        return $this->paginationIndex < ($this->modelCount - $this->pagination);
    }

    function getHeaders(): array
    {
        $headers = [];
        foreach (array_keys($this->columns) as $header){
            $headers[] = [
                'header' => $header,
                'propertyPath' => $this->columns[$header]['propertyPath'],
            ];
        }
        return $headers;
    }

    function getRows(): array
    {
        $rows = [];
        foreach ($this->paginatedModels as $model){
            $row = [];
            foreach ($this->columns as $column){
                $row[] = [
                    'value' => $this->getValue($model, $column['propertyPath']),
                    'anchor' => $column['route'] ? route($column['route'][0], $this->getValue($model, $column['route'][1])) : null,
                ];
            }
            $rows[] = $row;
        }
        return $rows;
    }

    function create(): self
    {
        $this->models = $this->builder->get();
        foreach ($this->searchCases as $propertyPath => $value){
            $this->models = $this->models->filter(function ($model) use ($propertyPath, $value){
                return str_contains($this->data_get($model, $propertyPath), $value);
            });
        }
        $this->models = $this->sortOrder == SortOrder::DESCENDING ? $this->models->sortByDesc($this->sortByColumn) : $this->models->sortBy($this->sortByColumn);
        $this->paginatedModels = $this->getPaginated();
        $this->modelCount = count($this->models);
        return $this;
    }

    function addSearchCase(string $propertyPath, string $value): self
    {
        $this->searchCases[$propertyPath] = $value;
        return $this;
    }

    function to(): int
    {
        return $this->paginationIndex + $this->pagination;
    }

    protected function getValue($model, $propertyPath): string|null
    {
        return array_reduce(explode('.', $propertyPath),
            function ($o, $p) {
                return is_numeric($p) ? ($o[$p] ?? null) : ($o->$p ?? null);
            }, $model
        );
    }

    protected function getPaginated(): Collection
    {
        return $this->models->skip($this->paginationIndex - 1)->take($this->pagination);
    }

    protected function data_get($target, string $propertyPath): string
    {
        $data = data_get($target, $propertyPath);
        if($data instanceof UnitEnum){
            $data = $data->value;
        }
        return $data;
    }
}
