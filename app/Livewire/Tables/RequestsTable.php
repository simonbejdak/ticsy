<?php

namespace App\Livewire\Tables;

use App\Helpers\Table\TableBuilder;
use App\Models\Request;
use Illuminate\Database\Eloquent\Builder;

class RequestsTable extends \App\Livewire\Table
{
    function query(): Builder
    {
        return Request::query()->with('caller');
    }

    function schema(): TableBuilder
    {
        return $this->tableBuilder()
            ->column('Number', 'id', ['requests.edit', 'id'])
            ->column('Caller', 'caller.name')
            ->column('Resolver', 'resolver.name')
            ->column('Status', 'status.value')
            ->column('Priority', 'priority.value');
    }
}
