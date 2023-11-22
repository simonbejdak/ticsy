<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SimpleProfileCard extends Component
{
    public User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function render(): View|Closure|string
    {
        return view('components.simple-profile-card');
    }
}
