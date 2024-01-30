<?php

namespace App\View\Components;

use App\Helpers\Table\Table;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TablePagination extends Component
{
    public Table $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function render(): View|Closure|string
    {
        return view('components.table-pagination');
    }
}
