<?php

namespace App\Livewire;

use App\Helpers\TabList;
use App\Interfaces\Fieldable;
use App\Models\Request;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class Tabs extends Component
{
    public Model $model;
    public array $tabs;
    public string $viewedTab;

    function mount(): void
    {
        $this->viewedTab = $this->tabs[0];
    }

    function setViewedTab(string $tab): void
    {
        if(!in_array($tab, $this->tabs)){
            abort(Response::HTTP_NOT_FOUND);
        }

        $this->viewedTab = $tab;
    }
}
