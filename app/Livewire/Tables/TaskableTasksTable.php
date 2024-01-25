<?php

namespace App\Livewire\Tables;

use App\Enums\SortOrder;
use App\Helpers\Table\Table;
use App\Interfaces\Taskable;
use App\Models\Task;

class TaskableTasksTable extends \App\Livewire\Table
{
    public Taskable $taskable;

    function table(): Table
    {
        return Table::make($this->taskable->tasks()->started()->getQuery())
            ->sortByColumn('id')
            ->sortOrder(SortOrder::ASCENDING)
            ->column('Number', 'id', ['tasks.edit', 'id'])
            ->column('Description', 'description')
            ->column('Status', 'status.value')
            ->get();
    }
}
