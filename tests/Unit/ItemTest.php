<?php

namespace Tests\Unit;

use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_many_categories()
    {
        $item = IncidentItem::firstOrFail();

        $this->assertEquals('Network', $item->categories()->findOrFail(IncidentCategory::NETWORK)->name);
        $this->assertEquals(
            'Application',
            $item->categories()->findOrFail(IncidentCategory::APPLICATION)->name
        );
    }

    public function test_it_has_many_tickets()
    {
        $item = IncidentItem::firstOrFail();
        $category = IncidentCategory::firstOrFail();

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
        $item = IncidentItem::findOrFail(IncidentItem::FAILED_NODE);

        $this->assertEquals('Failed Node', $item->name);
    }

}
