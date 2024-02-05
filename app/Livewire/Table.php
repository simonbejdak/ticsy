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
    public $itemsPerPage = 25;
    #[Locked]
    public int $modelCount;
    #[Locked]
    public array $properties;
    #[Locked]
    public bool $paginate;
    #[Locked]
    public bool $columnSearch;
    #[Locked]
    public string $propertyToSortBy = 'id';
    #[Locked]
    public SortOrder $sortOrder = SortOrder::DESCENDING;

    abstract function query(): Builder;
    abstract function schema(): TableBuilder;

    function tableBuilder(): TableBuilder{
        return \App\Helpers\Table\Table::make($this->query())
            ->sortByProperty($this->propertyToSortBy)
            ->sortOrder($this->sortOrder)
            ->itemsPerPage($this->itemsPerPage)
            ->paginationIndex($this->isPaginationIndexValid() ? $this->paginationIndex : 1)
            ->searchCases($this->searchCases);
    }

    function mount(): void
    {
        $table = $this->schema()->get();
        $this->modelCount = $table->modelCount;
        $this->paginate = $table->paginate;
        $this->columnSearch = $table->columnSearch;
        foreach($table->columns as $column){
            $this->properties[] = $column['property'];
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

    function columnHeaderClicked(string $property): void
    {
        if($this->isPropertyPathValid($property)){
            if($this->propertyToSortBy == $property){
                $this->switchSortOrder();
            } else {
                $this->sortOrder = SortOrder::ASCENDING;
            }
            $this->propertyToSortBy = $property;
            $this->render();
        }
    }

    function searchCase(string $property): void
    {
        if($this->isPropertyPathValid($property) && $this->columnSearch){
            $this->searchCases[$property] = $this->{$property};
        }
    }

    function doubleBackwardsClicked(): void
{
    $this->paginationIndex = 1;
}

    function backwardsClicked(): void
    {
        if($this->paginationIndex - $this->itemsPerPage < 1){
            $this->paginationIndex = 1;
        } else {
            $this->paginationIndex -= $this->itemsPerPage;
        }
    }

    function forwardClicked(): void
    {
        if($this->paginationIndex + $this->itemsPerPage > $this->modelCount - $this->itemsPerPage){
            $this->paginationIndex = $this->modelCount - $this->itemsPerPage;
        } else {
            $this->paginationIndex += $this->itemsPerPage;
        }
    }

    function doubleForwardClicked(): void
    {
        $this->paginationIndex = $this->modelCount - $this->itemsPerPage;
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

    protected function isPropertyPathValid(string $property): bool
    {
        return in_array($property, $this->properties);
    }
}
