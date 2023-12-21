<?php


use App\Models\Request;
use App\Models\Request\RequestItem;
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

    public function test_it_has_many_requests()
    {
        $item = RequestItem::firstOrFail();

        Request::factory(2, ['item_id' => $item])->create();

        $this->assertCount(2, $item->requests);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $item = RequestItem::findOrFail(RequestItem::FAILED_NODE);

        $this->assertEquals('Failed Node', $item->name);
    }

}
