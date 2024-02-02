<?php

namespace App\Livewire;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ResolverPanelOption extends Component
{
    #[Locked]
    public \App\Enums\ResolverPanelOption $option;
    #[Locked]
    public string $value;
    #[Locked]
    public string $route;
    #[Locked]
    public bool $selected;
    #[Locked]
    public bool $favorite;

    function mount(\App\Enums\ResolverPanelOption $option, bool $selected): void
    {
        $this->option = $option;
        $this->value = $this->option->value;
        $this->route = $this->option->route();
        $this->selected = $selected;
        $this->favorite = Auth::user()->hasFavoriteResolverPanelOption($this->option);
    }

    function render()
    {
        return view('livewire.resolver-panel-option');
    }

    function starClicked(): void
    {
        UserService::switchFavoriteResolverPanelOption(auth()->user(), $this->option);
        $this->dispatch('star-clicked');
    }
}
