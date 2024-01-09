<?php

namespace App\Livewire;

use App\Enums\Tab;
use App\Helpers\Tabs;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class TempTabs extends Component
{
    public Model $model;
    protected Tabs $tabs;
    protected Tab $viewedTab;

    function render(){
        return view('livewire.tabs');
    }

    function mount(Tabs $tabs): void
    {
        $this->tabs = $tabs;
        $this->viewedTab = $this->tabs->first();
    }

    function tabs(): Tabs
    {
        return $this->tabs;
    }

    function viewedTab(): Tab
    {
        return $this->viewedTab;
    }

    function setViewedTab(Tab $tab): void
    {
        if(!$this->tabs->has($tab)){
            abort(Response::HTTP_NOT_FOUND);
        }
        $this->viewedTab = $tab;
    }
}
