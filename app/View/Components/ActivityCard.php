<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActivityCard extends Component
{
    public array $activity;
    public function __construct(array $activity)
    {
        $this->activity = $activity;
    }

    public function render(): View|Closure|string
    {
        return view('components.activity-card');
    }
}
