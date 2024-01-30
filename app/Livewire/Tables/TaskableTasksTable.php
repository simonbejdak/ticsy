<?php

namespace App\Livewire\Tables;

use App\Enums\SortOrder;
use App\Helpers\Table\TableBuilder;
use App\Interfaces\Taskable;
use App\Livewire\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskableTasksTable extends Table
{
    public Taskable $taskable;
    public SortOrder $sortOrder = SortOrder::ASCENDING;

    function query(): Builder
    {
        return $this->taskable->tasks()->started()->getQuery();
    }

    function schema(): TableBuilder
    {
        return $this->tableBuilder()
            ->column('Number', 'id', ['tasks.edit', 'id'])
            ->column('Description', 'description')
            ->column('Status', 'status.value');
    }
}
