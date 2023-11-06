<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_it_has_many_tickets()
    {
        $category = Category::firstOrFail();

        Ticket::factory([
            'description' => 'Ticket Description 1',
            'category_id' => $category,
        ])->create();

        Ticket::factory([
            'description' => 'Ticket Description 2',
            'category_id' => $category,
        ])->create();

        $i = 1;
        foreach ($category->tickets as $ticket){
            $this->assertEquals('Ticket Description ' . $i, $ticket->description);
            $i++;
        }
    }

    public function test_it_belongs_to_many_items()
    {
        // Items are being attached to all Categories in TestDatabaseSeeder
        $category = Category::firstOrFail();

        $this->assertEquals('Issue', $category->items()->findOrFail(1)->name);
        $this->assertEquals('Failed Node', $category->items()->findOrFail(5)->name);
    }
}
