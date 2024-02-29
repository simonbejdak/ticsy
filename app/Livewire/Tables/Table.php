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

abstract class Table extends Component
{
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
    abstract function route(): string;

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

    function setSelectedColumnVisible(): void
    {
        if(
            in_array($this->selectedColumn, $this->columns) &&
            in_array($this->selectedColumn, $this->hiddenColumns) &&
            !in_array($this->selectedColumn, $this->visibleColumns)
        ) {
            $this->visibleColumns[] = $this->selectedColumn;
            unset($this->hiddenColumns[array_search($this->selectedColumn, $this->hiddenColumns)]);
            $this->render();
        }
    }

    function setSelectedColumnHidden()
    {
        if(
            in_array($this->selectedColumn, $this->columns) &&
            in_array($this->selectedColumn, $this->visibleColumns) &&
            !in_array($this->selectedColumn, $this->hiddenColumns)
        ) {
            $this->hiddenColumns[] = $this->selectedColumn;
            unset($this->visibleColumns[array_search($this->selectedColumn, $this->visibleColumns)]);
            Session::flash('success', 'You have successfully personalized your table');
            return redirect()->to($this->route());
        }
        return null;
    }

    function personalize()
    {
        if($this->areColumnsValid($this->visibleColumns)) {
            $personalization = $this->userPersonalization() ??
                TablePersonalization::make(['user_id' => Auth::user()->id, 'table_name' => get_class_name($this)]);

            $personalization->columns = '';
            foreach ($this->visibleColumns as $column){
                $personalization->columns .= $column . ',';
            }

            $personalization->save();
            $this->render();
//            Session::flash('success', 'You have successfully personalized the table');
//            return redirect()->route('tasks.edit', $this->task);
        }
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

    protected function areColumnsValid(array $columns): bool
    {
        foreach ($columns as $column){
            if(!in_array($column, $this->columns)){
                return false;
            }
        }
        return true;
    }

    protected function setColumns(): Columns
    {
        if($this->userPersonalization()){
            return $this->columns()->personalize($this->userPersonalization());
        }
        return $this->columns();
    }

    protected function userPersonalization(): TablePersonalization|null
    {
        return Auth::user()->tablePersonalization($this);
    }
}
