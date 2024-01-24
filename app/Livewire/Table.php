<?php

namespace App\Livewire;

use App\Helpers\Fields\Field;
use App\Helpers\Fields\TextInput;
use Livewire\Component;

class Table extends Component
{
    protected \App\Helpers\Table $table;
    public int $startingPaginationModel;

    public function mount(\App\Helpers\Table $table){
        $this->table = $table;
        $this->startingPaginationModel = $this->table->startingPaginationModel;
    }

    public function render()
    {
        return view('livewire.table', ['table' => $this->table]);
    }
}
