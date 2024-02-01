<?php

namespace App\Livewire;

use App\Enums\ResolverPanelTab;
use Livewire\Component;

class ResolverPanel extends Component
{
    public ResolverPanelTab $selectedTab = ResolverPanelTab::ALL;

    function render()
    {
        return view('livewire.resolver-panel');
    }

    function allTabClicked(): void
    {
        $this->selectedTab = ResolverPanelTab::ALL;
    }

    function favoritesTabClicked(): void
    {
        $this->selectedTab = ResolverPanelTab::FAVORITES;
    }
}
