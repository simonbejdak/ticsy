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

        Request::factory([
            'description' => 'Request Description 1',
            'category_id' => $category,
        ])->create();

        Request::factory([
            'description' => 'Request Description 2',
            'category_id' => $category,
        ])->create();

        $i = 1;
        foreach ($category->requests as $request){
            $this->assertEquals('Request Description ' . $i, $request->description);
            $i++;
        }
    }

    public function test_it_belongs_to_many_items()
    {
//        // Items are being attached to all Categories in TestDatabaseSeeder
//        $category = Category::firstOrFail();
//
//        $this->assertEquals('Issue', $category->items()->findOrFail(1)->name);
//        $this->assertEquals('Failed Node', $category->items()->findOrFail(5)->name);
    }
}
