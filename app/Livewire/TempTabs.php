<?php

namespace App\Livewire;

use App\Enums\Tab;
use App\Helpers\Tabs;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class TempTabs extends Component
{
    public Model $model;
    public array $tabs;
    protected Tab $viewedTab;

    function render(){
        return view('livewire.tabs');
    }

    function mount(array $tabs): void
    {
        $this->tabs = $tabs;
        $this->viewedTab = $this->tabs[0];
    }

    function tabs(): array
    {
        return $this->tabs;
    }

    function viewedTab(): Tab
    {
        return $this->viewedTab;
    }

    // I really wanted to use as argument Tab enum, but again, Livewire is not much of a help here, as it for some reason tries to instantiate enums, which results into PHP error
    function setViewedTab(string $tab): void
    {
        $tab = Tab::getEnumByValue($tab);

        if(!in_array($tab, $this->tabs)){
            abort(Response::HTTP_NOT_FOUND);
        }
        $this->viewedTab = $tab;
    }
}
