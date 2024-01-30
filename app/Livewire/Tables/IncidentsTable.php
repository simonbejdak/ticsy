<?php

namespace App\Livewire\Tables;

use App\Helpers\Table\TableBuilder;
use App\Livewire\Table;
use App\Models\Incident;
use Illuminate\Database\Eloquent\Builder;

class IncidentsTable extends Table
{
    function query(): Builder
    {
        return Incident::query()->with('caller');
    }

    function schema(): TableBuilder
    {
        return $this->tableBuilder()
            ->column('Number', 'id', ['incidents.edit', 'id'])
            ->column('Caller', 'caller.name')
            ->column('Resolver', 'resolver.name')
            ->column('Status', 'status.value')
            ->column('Priority', 'priority.value');
    }
}
