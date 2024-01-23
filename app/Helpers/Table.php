<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class Table
{
    public Builder $builder;
    public array $columns;
    public array $rows;

    function __construct(string $modelClass)
    {
        if(!is_subclass_of($modelClass, Model::class)){
            throw new InvalidArgumentException('You must pass a model class as an argument to Table constructor.');
        }
        $this->builder = $modelClass::query();
        $this->columns = Schema::getColumnListing($this->builder->getModel()->getTable());
        foreach ($this->builder->get() as $model){
            $row = [];
            foreach ($this->columns as $column){
                $row[] = $model->{$column};
            }
            $this->rows[] = $row;
        }

        dd($this->columns);
    }
}
