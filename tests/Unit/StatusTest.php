<?php

namespace Tests\Unit;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_many_tickets()
    {
        $status = Status::factory(['name' => 'Open'])->create();
        Ticket::factory(['description' => 'Ticket Description 1', 'status_id' => $status])->create();
        Ticket::factory(['description' => 'Ticket Description 2', 'status_id' => $status])->create();

        $tickets = $status->tickets;

        $i = 1;
        foreach ($tickets as $ticket){
            $this->assertEquals('Ticket Description '.$i, $ticket->description);
            $i++;
        }
    }

    public function test_it_has_correct_default_status(){
        $ticket = new Ticket();

        $this->assertEquals(Status::DEFAULT, $ticket->status->id);
    }
}
