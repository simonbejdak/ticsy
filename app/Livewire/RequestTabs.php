<?php

namespace App\Livewire;

use App\Models\Request;
use Livewire\Component;

class RequestTabs extends Component
{
    public Request $request;
    public string $viewedTab = 'tasks';

    function render()
    {
        return view('livewire.request-tabs');
    }

    function setViewedTab(string $tab): void
    {
        $this->viewedTab = $tab;
    }

}
