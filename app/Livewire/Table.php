<?php

namespace App\Livewire;

use App\Enums\SortOrder;
use App\Helpers\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class Table extends Component
{
    public $paginationIndex = 1;
    #[Locked]
    public $pagination = 25;
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
            ->paginate($this->pagination)
            ->paginationIndex($this->isPaginationIndexValid() ? $this->paginationIndex : 1);
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

    function doubleBackwardsClicked(): void
{
    $this->paginationIndex = 1;
}

    function backwardsClicked(): void
    {
        if($this->paginationIndex - $this->pagination < 1){
            $this->paginationIndex = 1;
        } else {
            $this->paginationIndex -= $this->pagination;
        }
    }

    function forwardClicked(): void
    {
        if($this->paginationIndex + $this->pagination > $this->modelCount - $this->pagination){
            $this->paginationIndex = $this->modelCount - $this->pagination;
        } else {
            $this->paginationIndex += $this->pagination;
        }
    }

    function doubleForwardClicked(): void
    {
        $this->paginationIndex = $this->modelCount - $this->pagination;
    }

    protected function switchSortOrder(): void
    {
        $this->sortOrder == SortOrder::DESCENDING ? $this->sortOrder = SortOrder::ASCENDING : $this->sortOrder = SortOrder::DESCENDING;
    }

    protected function isPaginationIndexValid(): bool
    {
        if(is_numeric($this->paginationIndex)){
            if($this->paginationIndex == 1){
                return true;
            } else {
                if($this->paginationIndex > 1 && $this->paginationIndex <= $this->modelCount){
                    return true;
                }
            }
        }
        return false;
    }
}
