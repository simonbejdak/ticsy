<?php

namespace App\Livewire\Tables;

use App\Helpers\Columns\Columns;
use App\Helpers\Table\TableBuilder;
use App\Models\TablePersonalization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

abstract class ExtendedTable extends Table
{
    public array $searchCases = [];
    public array $hiddenColumns = [];
    public array $visibleColumns = [];
    public $paginationIndex = 1;
    #[Locked]
    public $itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
    #[Locked]
    public array $properties;

    abstract function route(): string;

    public function rules(): array
    {
        return [
            'visibleColumns.*' => ['required', Rule::in($this->columns)],
        ];
    }

    function tableBuilder(): TableBuilder{
        return \App\Helpers\Table\ExtendedTable::make($this->query())
            ->sortProperty($this->sortProperty)
            ->sortOrder($this->sortOrder)
            ->columns($this->visibleColumns())
            ->itemsPerPage($this->itemsPerPage)
            ->paginationIndex($this->isPaginationIndexValid() ? $this->paginationIndex : 1)
            ->searchCases($this->searchCases);
    }

    function mount(): void
    {
        $table = $this->table();
        $this->count = $table->count;
        foreach($table->columns as $column){
            $this->properties[] = $column->property;
        }
        $this->columns = $this->columns()->headers();
        $this->hiddenColumns = $this->hiddenColumns()->headers();
        $this->visibleColumns = $this->visibleColumns()->headers();
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

    function personalize()
    {
        $this->validate();
        $personalization = $this->userPersonalization() ??
            TablePersonalization::make(['user_id' => Auth::user()->id, 'table_name' => get_class_name($this)]);

        $personalization->columns = '';
        foreach ($this->visibleColumns as $column){
            $personalization->columns .= $column . ',';
        }
        $personalization->save();
        Session::flash('success', 'You have successfully personalized the table');
        return redirect()->to($this->route());
    }

    function hiddenColumns(): Columns
    {
        return $this->setColumns()->hidden();
    }

    function visibleColumns(): Columns
    {
        return $this->setColumns()->visible();
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

    protected function setColumns(): Columns
    {
        if($this->userPersonalization()){
            return $this->columns()->personalize($this->userPersonalization());
        }
        return parent::setColumns();
    }

    protected function userPersonalization(): TablePersonalization|null
    {
        return Auth::user()->tablePersonalization($this);
    }

    protected function isSelectedColumnInVisibleColumns(): bool
    {
        return in_array($this->selectedColumn, $this->visibleColumns);
    }

    protected function isSelectedColumnInHiddenColumns(): bool
    {
        return in_array($this->selectedColumn, $this->hiddenColumns);
    }
}
