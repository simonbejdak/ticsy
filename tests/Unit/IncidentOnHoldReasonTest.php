<?php


use App\Models\Incident\Incident;
use App\Models\Incident\IncidentOnHoldReason;
use App\Models\Incident\IncidentStatus;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncidentOnHoldReasonTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_many_tickets()
    {
        $onHoldReason = IncidentOnHoldReason::firstOrFail();
        Incident::factory(2, ['on_hold_reason_id' => $onHoldReason,])->statusOnHold()->create();

        $this->assertCount(2, $onHoldReason->incidents);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $onHoldReason = IncidentOnHoldReason::findOrFail(IncidentOnHoldReason::WAITING_FOR_VENDOR);

        $this->assertEquals('Waiting For Vendor', $onHoldReason->name);
    }

    public function test_exception_thrown_if_status_on_hold_reason_assigned_but_status_different_than_on_hold()
    {
        $onHoldReason = IncidentOnHoldReason::findOrFail(IncidentOnHoldReason::CALLER_RESPONSE);

        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason cannot be assigned to Incident if IncidentStatus is not on hold');

        Incident::factory(['status_id' => IncidentStatus::OPEN,
            'on_hold_reason_id' => $onHoldReason
        ])->create();
    }

    public function test_exception_thrown_if_status_on_hold_selected_but_status_on_hold_reason_is_empty()
    {
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason must be assigned to Incident if IncidentStatus is on hold');

        Incident::factory()->statusOnHold()->create();
    }
}
