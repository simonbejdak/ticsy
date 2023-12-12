<?php


use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlaTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_slable()
    {
        // Ticket is SLAble, as per its configuration

        $slable = Ticket::factory()->create();
        $sla = $slable->sla;

        $this->assertEquals($slable->id, $sla->slable->id);
    }
}
