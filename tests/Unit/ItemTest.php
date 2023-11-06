<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Item;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_many_categories()
    {
        $item = Item::firstOrFail();

        $this->assertEquals('Network', $item->categories()->findOrFail(TicketConfig::CATEGORIES['network'])->name);
        $this->assertEquals(
            'Application',
            $item->categories()->findOrFail(TicketConfig::CATEGORIES['application'])->name
        );
    }

    public function test_it_has_many_tickets()
    {
        $item = Item::firstOrFail();
        $category = Category::firstOrFail();

        Ticket::factory(['description' => 'Ticket Description 1',
            'category_id' => $category,
            'item_id' => $item,
        ])->create();

        Ticket::factory([
            'description' => 'Ticket Description 2',
            'category_id' => $category,
            'item_id' => $item,
        ])->create();

        $this->assertEquals('Ticket Description 1', $item->tickets()->findOrFail(1)->description);
        $this->assertEquals('Ticket Description 2', $item->tickets()->findOrFail(2)->description);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $item = Item::findOrFail(TicketConfig::ITEMS['failed_node']);

        $this->assertEquals('Failed Node', $item->name);
    }

}
