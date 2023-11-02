<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketFieldSelectItem extends TicketFieldSelect
{
    public Category|null $category;
    public function __construct($category)
    {
        parent::__construct();

        $this->category = Category::find($category) ?? null;
        $this->name = 'item';
        $this->options = $this->category ? $this->toIterable($this->category->items()->get()) : [];
        $this->blank = true;
    }
}
