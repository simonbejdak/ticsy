<?php

namespace App\Livewire\Tables;

use App\Helpers\Table\TableBuilder;
use App\Livewire\Table;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;

class TasksTable extends Table
{
    function query(): Builder
    {
        return Task::query()->started()->with('caller');
    }

    function schema(): TableBuilder
    {
        return $this->tableBuilder()
            ->column('Number', 'id', ['tasks.edit', 'id'])
            ->column('Description', 'description')
            ->column('Caller', 'caller.name')
            ->column('Resolver', 'resolver.name')
            ->column('Status', 'status.value')
            ->column('Priority', 'priority.value');
    }
}
