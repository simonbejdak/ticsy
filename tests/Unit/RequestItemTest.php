<?php


use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_many_request_categories()
    {
        // Items are being attached to all Categories in TestDatabaseSeeder
        $item = RequestItem::factory()->create();
        $categories = RequestCategory::factory(3)->create();

        $item->categories()->attach($categories);

        $this->assertGreaterThan(1, count($item->categories));
    }

    public function test_it_has_many_requests()
    {
        $item = RequestItem::firstOrFail();
        $category = $item->randomCategory();

        Request::factory(2, ['category_id' => $category, 'item_id' => $item])->create();

        $this->assertCount(2, $item->requests);
    }
}
