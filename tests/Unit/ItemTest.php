<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Item;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_many_categories()
    {
        $categoryOne = Category::factory(['name' => 'Category 1'])->create();
        $categoryTwo = Category::factory(['name' => 'Category 2'])->create();

        $item = Item::factory()->create();
        $item->categories()->attach($categoryOne);
        $item->categories()->attach($categoryTwo);

        $i = 1;
        foreach ($item->categories as $category) {
            $this->assertEquals('Category ' . $i, $category->name);
            $i++;
        }
    }

    public function test_it_has_many_tickets()
    {
        $category = Category::firstOrFail();
        $item = $category->items()->inRandomOrder()->first();

        Ticket::factory(['description' => 'Ticket Description 1',
            'category_id' => $category,
            'item_id' => $item,
        ])->create();

        Ticket::factory([
            'description' => 'Ticket Description 2',
            'category_id' => $category,
            'item_id' => $item,
        ])->create();

        $i = 1;
        foreach ($item->tickets as $ticket) {
            $this->assertEquals('Ticket Description ' . $i, $ticket->description);
            $i++;
        }
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $item = Item::factory(['name' => 'item_name'])->create();

        $this->assertEquals('Item Name', $item->name);
    }
}
