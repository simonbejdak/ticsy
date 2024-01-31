<?php

namespace App\Livewire;

use App\Enums\SortOrder;
use App\Helpers\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;
use UnitEnum;

abstract class Table extends Component
{
    public $paginationIndex = 1;
    public array $searchCases = [];
    #[Locked]
    public $pagination = 25;
    #[Locked]
    public int $modelCount;
    #[Locked]
    public array $propertyPaths;
    #[Locked]
    public bool $paginate;
    #[Locked]
    public bool $columnSearch;
    #[Locked]
    public string $columnToSortBy = 'id';
    #[Locked]
    public SortOrder $sortOrder = SortOrder::DESCENDING;

    abstract function query(): Builder;
    abstract function schema(): TableBuilder;

    function tableBuilder(): TableBuilder{
        $tableBuilder = \App\Helpers\Table\Table::make($this->query())
            ->sortByColumn($this->columnToSortBy)
            ->sortOrder($this->sortOrder)
            ->paginate($this->pagination)
            ->paginationIndex($this->isPaginationIndexValid() ? $this->paginationIndex : 1);

        foreach($this->searchCases as $propertyPath => $value){
            if(is_array($value)){
                $value = array_values($value)[0];
            }
            $tableBuilder->searchCase($propertyPath, $value);
        }

        return $tableBuilder;
    }

    function mount(): void
    {
        $table = $this->schema()->get();
        $this->modelCount = $table->modelCount;
        $this->paginate = $table->paginate;
        $this->columnSearch = $table->columnSearch;
        foreach($table->columns as $column){
            $this->propertyPaths[] = $column['propertyPath'];
        }
    }

    function table(): \App\Helpers\Table\Table
    {
        return $this->schema()->get();
    }

    function render()
    {
        return view('livewire.table', ['table' => $this->table()]);
    }

    function columnHeaderClicked(string $column): void
    {
        if($this->isPropertyPathValid($column)){
            if($this->columnToSortBy == $column){
                $this->switchSortOrder();
            } else {
                $this->sortOrder = SortOrder::ASCENDING;
            }
            $this->columnToSortBy = $column;
            $this->render();
        }
    }

    function searchCase(string $propertyPath): void
    {
        if($this->isPropertyPathValid($propertyPath) && $this->columnSearch){
            $this->searchCases[$propertyPath] = $this->{$propertyPath};
        }
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

    protected function isPropertyPathValid(string $propertyPath): bool
    {
        return in_array($propertyPath, $this->propertyPaths);
    }
}
