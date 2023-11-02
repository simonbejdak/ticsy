<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_it_has_many_tickets()
    {
        $category = Category::firstOrFail();

        Ticket::factory([
            'description' => 'Ticket Description 1',
            'category_id' => $category,
        ])->create();

        Ticket::factory([
            'description' => 'Ticket Description 2',
            'category_id' => $category,
        ])->create();

        $i = 1;
        foreach ($category->tickets as $ticket){
            $this->assertEquals('Ticket Description ' . $i, $ticket->description);
            $i++;
        }
    }

    public function test_it_belongs_to_many_items()
    {
        $itemOne = Item::factory([
            'name' => 'Item 1',
        ])->create();

        $itemTwo = Item::factory([
            'name' => 'Item 2',
        ])->create();

        $category = Category::factory()->create();
        $category->items()->attach($itemOne);
        $category->items()->attach($itemTwo);

        $i = 1;
        foreach ($category->items as $item){
            $this->assertEquals('Item ' . $i, $item->name);
            $i++;
        }
    }
}
