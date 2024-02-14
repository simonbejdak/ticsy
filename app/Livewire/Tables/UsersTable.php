<?php

namespace App\Livewire\Tables;

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
        return $this->tableBuilder()
            ->column('E-mail', 'email', ['users.edit', 'id'])
            ->column('Name', 'name')
            ->column('Location', 'location.value')
            ->column('Status', 'status.value');

    }
}
