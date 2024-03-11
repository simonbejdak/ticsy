<?php

namespace App\Livewire\Tables;

use App\Enums\SortOrder;
use App\Helpers\Columns\Columns;
use App\Helpers\Table\TableBuilder;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class Table extends Component
{
    const DEFAULT_ITEMS_PER_PAGE = 25;

    #[Locked]
    public int $count;
    #[Locked]
    public array $properties;
    #[Locked]
    public array $columns = [];
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
            ->columns($this->getColumns());
    }

    function mount(): void
    {
        $table = $this->table();
        foreach($table->columns as $column){
            $this->properties[] = $column->property;
        }
        $this->columns = $this->columns()->headers();
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

    protected function switchSortOrder(): void
    {
        $this->sortOrder == SortOrder::DESCENDING ? $this->sortOrder = SortOrder::ASCENDING : $this->sortOrder = SortOrder::DESCENDING;
    }

    protected function isPropertyValid(string $property): bool
    {
        return in_array($property, $this->properties);
    }

    protected function areColumnsValid(array $columns): bool
    {
        foreach ($columns as $column){
            if(!in_array($column, $this->columns)){
                return false;
            }
        }
        return true;
    }

    protected function getColumns(): Columns
    {
        return $this->columns();
    }
}
