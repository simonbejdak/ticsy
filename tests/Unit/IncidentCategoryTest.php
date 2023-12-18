<?php

namespace Tests\Unit;

use App\Models\Incident\Incident;
use App\Models\Incident\IncidentCategory;
use Tests\TestCase;

class IncidentCategoryTest extends TestCase
{
    public function test_it_has_many_incidents()
    {
        $category = IncidentCategory::firstOrFail();

        Incident::factory(2, ['category_id' => $category])->create();

        $this->assertCount(2, $category->incidents);
    }

    public function test_it_belongs_to_many_items()
    {
        // Items are being attached to all Categories in TestDatabaseSeeder
        $category = IncidentCategory::firstOrFail();

        $this->assertEquals('Issue', $category->items()->findOrFail(1)->name);
        $this->assertEquals('Failed Node', $category->items()->findOrFail(5)->name);
    }
}
