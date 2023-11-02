<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Status;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketFieldSelectCategory extends TicketFieldSelect
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'category';
        $this->options = $this->toIterable(Category::all());
        $this->blank = true;
    }
}
