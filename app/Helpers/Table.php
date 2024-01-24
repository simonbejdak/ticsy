<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

class Table
{
    public Builder $builder;
    public array $columns;
    public int $pagination;
    public int $startingPaginationModel;
    public Collection $models;

    static function make(Builder $builder): self
    {
        $static = new static();
        $static->builder = $builder;
        $static->pagination = 25;
        $static->startingPaginationModel = 1;
        $static->models = $static->getPaginated();
        return $static;
    }

    function column(string $title, string $propertyPath, array $route = null): self
    {
        $this->columns[$title] = [
            'propertyPath' => $propertyPath,
            'route' => $route,
        ];
        return $this;
    }

    function paginate(int $number): self
    {
        $this->pagination = $number;
        return $this;
    }

    function getHeaders(): array
    {
        return array_keys($this->columns);
    }

    function getRows(): array
    {
        $rows = [];
        foreach ($this->models as $model){
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

    protected function getValue($model, $propertyPath): string|null
    {
        return array_reduce(explode('.', $propertyPath), function ($o, $p) { return is_numeric($p) ? ($o[$p] ?? null) : ($o->$p ?? null); }, $model);
    }

    protected function getPaginated(): Collection
    {
        return $this->builder->skip($this->startingPaginationModel - 1)->take($this->pagination)->get();
    }
}
