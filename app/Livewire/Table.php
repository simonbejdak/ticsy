<?php

namespace App\Livewire;

use Livewire\Component;

class Table extends Component
{
    public \App\Helpers\Table $table;

    public function mount(\App\Helpers\Table $table){
        $this->table = $table;
    }

    public function render()
    {
        return view('livewire.table');
    }
}
