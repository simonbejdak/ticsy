<?php

namespace App\Livewire;

use App\Enums\ResolverPanelTab;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ResolverPanel extends Component
{
    public ResolverPanelTab $selectedTab;
    #[Locked]
    public array $options;
    #[Locked]
    public string $currentRoute;

    function mount(): void
    {
        $this->selectedTab = ResolverPanelTab::ALL;
        $this->currentRoute = Route::current()->getName();
    }

    function render()
    {
        $this->selectedTab = Auth::user()->getSelectedResolverPanelTab();
        if($this->selectedTab == ResolverPanelTab::FAVORITES){
            $this->options = Auth::user()->getFavoriteResolverPanelOptions();
        } else {
            $this->options = \App\Enums\ResolverPanelOption::cases();
        }
        return view('livewire.resolver-panel');
    }

    function allTabClicked(): void
    {
        UserService::setSelectedResolverPanelTab(Auth::user(), ResolverPanelTab::ALL);
    }

    function favoritesTabClicked(): void
    {
        UserService::setSelectedResolverPanelTab(Auth::user(), ResolverPanelTab::FAVORITES);
    }

    #[On('star-clicked')]
    public function starClicked(): void
    {
        $this->render();
    }
}
