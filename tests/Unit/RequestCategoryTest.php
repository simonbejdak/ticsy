<?php


use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
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
        $category = RequestCategory::factory()->create();
        $items = RequestItem::factory(3)->create();

        $category->items()->attach($items);

        $this->assertGreaterThan(1, count($category->items));
    }
}
