<?php

namespace App\Livewire\Tables;

use App\Enums\SortOrder;
use App\Helpers\Columns\Column;
use App\Helpers\Columns\ColumnRoute;
use App\Helpers\Columns\Columns;
use App\Helpers\Table\TableBuilder;
use App\Interfaces\Taskable;
use Illuminate\Database\Eloquent\Builder;

class TaskableTasksTable extends SimpleTable
{
    public Taskable $taskable;
    public SortOrder $sortOrder = SortOrder::ASCENDING;

    function query(): Builder
    {
        return $this->taskable->tasks()->started()->getQuery();
    }

    function columns(): Columns
    {
        return Columns::create(
            Column::create('Number', 'id', ColumnRoute::create('tasks.edit', ['id'])),
            Column::create('Description', 'description'),
            Column::create('Status', 'status.value')
        );
    }
}
