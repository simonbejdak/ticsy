<?php

namespace App\Helpers\Table;

use App\Enums\SortOrder;
use App\Helpers\Columns\Column;
use App\Helpers\Columns\Columns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class Table
{
    public Builder $builder;
    public Columns $columns;
    public int $count;
    public string $sortProperty;
    public SortOrder $sortOrder;
    public Collection $collection;

    protected function __construct(){}

    static function make(Builder $builder): TableBuilder
    {
        $static = new static();
        $static->builder = $builder;
        $static->columns = Columns::create();
        $static->sortOrder = SortOrder::ASCENDING;
        return new TableBuilder($static);
    }

    function getHeaders(): array
    {
        $headers = [];
        foreach ($this->columns as $column){
            $headers[] = [
                'header' => $column->header,
                'property' => $column->property,
            ];
        }
        return $headers;
    }

    function getRows(): array
    {
        $rows = [];
        foreach ($this->collection as $model){
            $row = [];
            foreach ($this->columns as $column){
                $row[] = [
                    'value' => $this->getValue($model, $column->property),
                    'route' => $this->getRoute($model, $column)
                ];
            }
            $rows[] = $row;
        }
        return $rows;
    }

    protected function getValue(Model $model, string $property): string|null
    {
        return array_reduce(explode('.', $property),
            function ($o, $p) {
                return is_numeric($p) ? ($o[$p] ?? null) : ($o->$p ?? null);
            }, $model
        );
    }

    protected function getRoute(Model $model, Column $column): string|null
    {
        if(isset($column->route)){
            $arguments = [];
            foreach ($column->route->arguments as $argument){
                if($this->getValue($model, $argument) == null) {
                    return null;
                } else {
                    $arguments[] = $this->getValue($model, $argument);
                }
            }
            return route($column->route->name, $arguments);
        }
        return null;
    }

    // just get data from model based on provided property in dot notation, i.e. status.route
    protected function data_get($model, string $property): string
    {
        $data = data_get($model, $property);
        if($data instanceof UnitEnum){
            $data = $data->value;
        }
        return $data;
    }

    function create(): self
    {
        $this->collection = $this->builder->get();
        $this->collection = $this->sortOrder == SortOrder::DESCENDING ? $this->collection->sortByDesc($this->sortProperty) : $this->collection->sortBy($this->sortProperty);
        $this->count = count($this->collection);
        return $this;
    }
}
