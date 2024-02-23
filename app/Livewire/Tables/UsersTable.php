<?php

namespace App\Livewire\Tables;

use App\Helpers\Columns\Column;
use App\Helpers\Columns\ColumnRoute;
use App\Helpers\Columns\Columns;
use App\Helpers\Table\TableBuilder;
use App\Livewire\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UsersTable extends Table
{
    function query(): Builder
    {
        return User::query()->with('configurationItems');
    }

    function schema(): TableBuilder
    {
        return $this->tableBuilder();
    }

    function columns(): Columns
    {
        return Columns::create(
            Column::create('E-mail', 'email', ColumnRoute::create('users.edit', ['id'])),
            Column::create('Name', 'name'),
            Column::create('Location', 'location.value'),
            Column::create('Status', 'status.value'),
        );
    }
}
