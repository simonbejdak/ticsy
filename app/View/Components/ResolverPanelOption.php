<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ResolverPanelOption extends Component
{
    public string $value;
    public string $route;

    public function __construct(string $value, string $route)
    {
        $this->value = $value;
        $this->route = $route;
    }

    public function render(): View|Closure|string
    {
        return view('components.resolver-panel-option');
    }
}
