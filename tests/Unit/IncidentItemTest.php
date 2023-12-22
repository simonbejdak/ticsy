<?php

namespace Tests\Unit;

use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncidentItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_many_categories()
    {
        $item = IncidentItem::firstOrFail();

        $this->assertGreaterThan(1, count($item->categories));
    }

    public function test_it_has_many_incidents()
    {
        $item = IncidentItem::firstOrFail();
        $category = IncidentCategory::firstOrFail();

        Incident::factory(2, [
            'category_id' => $category,
            'item_id' => $item,
        ])->create();

        $this->assertCount(2, $item->incidents);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $item = IncidentItem::findOrFail(IncidentItem::FAILED_NODE);

        $this->assertEquals('Failed Node', $item->name);
    }

}
