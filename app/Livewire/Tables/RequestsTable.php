<?php

namespace App\Livewire\Tables;

use App\Helpers\Columns\Column;
use App\Helpers\Columns\ColumnRoute;
use App\Helpers\Columns\Columns;
use App\Livewire\Table;
use App\Models\Request;
use Illuminate\Database\Eloquent\Builder;

class RequestsTable extends Table
{
    function query(): Builder
    {
        return Request::query()->with('caller');
    }

    function columns(): Columns
    {
        return Columns::create(
            Column::create('Number', 'id', ColumnRoute::create('requests.edit', ['id'])),
            Column::create('Description', 'description'),
            Column::create('Caller', 'caller.name'),
            Column::create('Category', 'category.name')->hidden(),
            Column::create('Item', 'item.name')->hidden(),
            Column::create('Resolver', 'resolver.name'),
            Column::create('Status', 'status.value'),
            Column::create('Priority', 'priority.value'),
            Column::create('Created at', 'created_at')->hidden(),
            Column::create('Updated at', 'updated_at')->hidden(),
        );
    }
}
