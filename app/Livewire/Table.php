<?php

namespace App\Livewire;

use App\Enums\SortOrder;
use Livewire\Component;

abstract class Table extends Component
{
    public string $columnToSortBy;
    public SortOrder $sortOrder;

    abstract function table(): \App\Helpers\Table\Table;

    function render()
    {
        return view('livewire.table', ['table' => $this->table()]);
    }

    function columnHeaderClicked(string $column): void
    {
        if($this->columnToSortBy == $column){
            $this->switchSortOrder();
        }
        $this->columnToSortBy = $column;
    }

    protected function switchSortOrder(): void
    {
        $this->sortOrder == SortOrder::DESCENDING ? $this->sortOrder = SortOrder::ASCENDING : $this->sortOrder = SortOrder::DESCENDING;
    }
}
