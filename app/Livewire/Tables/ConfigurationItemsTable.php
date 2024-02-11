<?php

namespace App\Livewire\Tables;

use App\Enums\SortOrder;
use App\Helpers\Table\TableBuilder;
use App\Livewire\Table;
use App\Models\ConfigurationItem;
use App\Models\Incident;
use Illuminate\Database\Eloquent\Builder;

class ConfigurationItemsTable extends Table
{
    function query(): Builder
    {
        return ConfigurationItem::query()->with('user');
    }

    function schema(): TableBuilder
    {
        return $this->tableBuilder()
            ->column('Serial Number', 'serial_number', ['configuration-items.edit', 'id'])
            ->column('User', 'user.name')
            ->column('Location', 'location.value');
    }
}
