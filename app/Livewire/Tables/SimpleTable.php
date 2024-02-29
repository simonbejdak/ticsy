<?php

namespace App\Livewire\Tables;

use App\Enums\SortOrder;
use App\Helpers\Columns\Columns;
use App\Helpers\Table\TableBuilder;
use App\Models\TablePersonalization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class SimpleTable extends Table
{
    function schema(): TableBuilder
    {
        return $this->tableBuilder()->simple();
    }
}
