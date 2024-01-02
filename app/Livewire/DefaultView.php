<?php

namespace App\Livewire;

use App\Helpers\Fields;
use App\Interfaces\Viewable;
use Livewire\Component;

class DefaultView extends Component
{
    public Viewable $viewable;
    public Fields $fieldsInGrid;
    public Fields $fieldsOutsideGrid;

    function mount(Viewable $viewable){
        $this->fieldsInGrid = $viewable->fields()->inGrid();
        $this->fieldsOutsideGrid = $viewable->fields()->outsideGrid();
    }

    function render(){
        return view('livewire.default-view');
    }
}
