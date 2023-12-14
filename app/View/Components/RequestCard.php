<?php

namespace App\View\Components;

use App\Models\Request;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RequestCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Request $request,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.request-card');
    }
}
