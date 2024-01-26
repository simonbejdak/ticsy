<?php

namespace App\Livewire;

use App\Enums\SortOrder;
use App\Helpers\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

abstract class Table extends Component
{
    #[Validate]
    public int|null $paginationIndex = 1;
    #[Locked]
    public int $modelCount;
    #[Locked]
    public string $columnToSortBy = 'id';
    #[Locked]
    public SortOrder $sortOrder = SortOrder::DESCENDING;

    abstract function query(): Builder;
    abstract function schema(): TableBuilder;

    function tableBuilder(): TableBuilder{
        return \App\Helpers\Table\Table::make($this->query())
            ->sortByColumn($this->columnToSortBy)
            ->sortOrder($this->sortOrder)
            ->paginationIndex($this->paginationIndex ?? 1);
    }

    function table(): \App\Helpers\Table\Table
    {
        $table = $this->schema()->get();
        $this->modelCount = $table->modelCount;
        return $table;
    }

    function rules(): array
    {
        return [
            'paginationIndex' => 'nullable|integer|lt:' . $this->modelCount,
        ];
    }

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
        $this->render();
    }

    protected function switchSortOrder(): void
    {
        $this->sortOrder == SortOrder::DESCENDING ? $this->sortOrder = SortOrder::ASCENDING : $this->sortOrder = SortOrder::DESCENDING;
    }
}
