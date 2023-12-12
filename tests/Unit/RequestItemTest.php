<?php


use App\Models\Category;
use App\Models\Item;
use App\Models\RequestItem;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_many_request_categories()
    {
        // Items are being attached to all Categories in TestDatabaseSeeder
        $item = RequestItem::firstOrFail();

        $this->assertGreaterThan(1, count($item->categories));
    }

    public function test_it_has_many_tickets()
    {
//        $item = Item::firstOrFail();
//        $category = Category::firstOrFail();
//
//        Ticket::factory(['description' => 'Ticket Description 1',
//            'category_id' => $category,
//            'item_id' => $item,
//        ])->create();
//
//        Ticket::factory([
//            'description' => 'Ticket Description 2',
//            'category_id' => $category,
//            'item_id' => $item,
//        ])->create();
//
//        $this->assertEquals('Ticket Description 1', $item->tickets()->findOrFail(1)->description);
//        $this->assertEquals('Ticket Description 2', $item->tickets()->findOrFail(2)->description);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
//        $item = Item::findOrFail(Item::FAILED_NODE);
//
//        $this->assertEquals('Failed Node', $item->name);
    }

}
