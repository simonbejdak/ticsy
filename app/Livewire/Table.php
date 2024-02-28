<?php

namespace App\Livewire;

use App\Enums\SortOrder;
use App\Helpers\Columns\Columns;
use App\Helpers\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class Table extends Component
{
    const DEFAULT_ITEMS_PER_PAGE = 25;

    public array $searchCases = [];
    public array $hiddenColumns = [];
    public array $visibleColumns = [];
    public $paginationIndex = 1;
    #[Locked]
    public $itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
    #[Locked]
    public int $count;
    #[Locked]
    public array $properties;
    #[Locked]
    public array $columns = [];
    #[Locked]
    public bool $paginate;
    #[Locked]
    public bool $columnTextSearch;
    #[Locked]
    public string $sortProperty = 'id';
    #[Locked]
    public SortOrder $sortOrder = SortOrder::DESCENDING;

    abstract function query(): Builder;
    abstract function columns(): Columns;

    function schema(): TableBuilder
    {
        return $this->tableBuilder();
    }

    function tableBuilder(): TableBuilder{
        return \App\Helpers\Table\Table::make($this->query())
            ->sortProperty($this->sortProperty)
            ->sortOrder($this->sortOrder)
            ->itemsPerPage($this->itemsPerPage)
            ->paginationIndex($this->isPaginationIndexValid() ? $this->paginationIndex : 1)
            ->searchCases($this->searchCases)
            ->columns($this->visibleColumns());
    }

    function mount(): void
    {
        $table = $this->table();
        $this->count = $table->count;
        $this->paginate = $table->paginate;
        $this->columnTextSearch = $table->columnTextSearch;
        foreach($table->columns as $column){
            $this->properties[] = $column->property;
        }
        $this->columns = $this->columns()->headers();
        $this->hiddenColumns = $this->hiddenColumns()->headers();
        $this->visibleColumns = $this->visibleColumns()->headers();
    }

    function table(): \App\Helpers\Table\Table
    {
        return $this->schema()->create();
    }

    function render()
    {
        return view('livewire.table', ['table' => $this->table()]);
    }

    function columnHeaderClicked(string $property): void
    {
        if($this->isPropertyValid($property)){
            if($this->sortProperty == $property){
                $this->switchSortOrder();
            } else {
                $this->sortOrder = SortOrder::ASCENDING;
            }
            $this->sortProperty = $property;
            $this->render();
        }
    }

    function searchCase(string $property): void
    {
        if($this->isPropertyValid($property) && $this->columnTextSearch){
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
        if($this->paginationIndex + $this->itemsPerPage > $this->count - $this->itemsPerPage){
            $this->paginationIndex = $this->count - $this->itemsPerPage;
        } else {
            $this->paginationIndex += $this->itemsPerPage;
        }
    }

    function doubleForwardClicked(): void
    {
        $this->paginationIndex = $this->count - $this->itemsPerPage;
    }

    function hiddenColumns(): Columns
    {
        return $this->setColumns()->hidden();
    }

    function visibleColumns(): Columns
    {
        return $this->setColumns()->visible();
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
                if($this->paginationIndex > 1 && $this->paginationIndex <= $this->count){
                    return true;
                }
            }
        }
        return false;
    }

    protected function isPropertyValid(string $property): bool
    {
        return in_array($property, $this->properties);
    }

    protected function setColumns(): Columns
    {
        if(Auth::user()->tableConfiguration($this)){
            return $this->columns()->configuration(Auth::user()->tableConfiguration($this));
        }
        return $this->columns();
    }
}
