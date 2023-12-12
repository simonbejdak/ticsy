<?php


use App\Models\Category;
use App\Models\Request;
use App\Models\RequestCategory;
use Tests\TestCase;

class RequestCategoryTest extends TestCase
{
    public function test_it_has_many_requests()
    {
        $category = RequestCategory::firstOrFail();
        Request::factory(2, ['category_id' => $category])->create();

        $this->assertCount(2, $category->requests);
    }

    public function test_it_belongs_to_many_request_items()
    {
        // Items are being attached to all Categories in TestDatabaseSeeder
        $category = RequestCategory::firstOrFail();

        $this->assertGreaterThan(1, count($category->items));
    }
}
