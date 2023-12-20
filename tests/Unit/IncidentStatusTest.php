<?php

namespace Tests\Unit;

use App\Models\Incident\Incident;
use App\Models\Incident\IncidentStatus;
use App\Models\Status;
use App\Models\TicketConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncidentStatusTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_many_incidents()
    {
        $status = Status::findOrFail(Status::IN_PROGRESS);
        Incident::factory(2, ['status_id' => $status])->create();

        $this->assertCount(2, $status->incidents);
    }

    public function test_incident_has_correct_default_status(){
        $incident = new Incident();

        $this->assertEquals(Incident::DEFAULT_STATUS, $incident->status->id);
    }
}
