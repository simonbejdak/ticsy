<?php


use App\Models\Group;
use App\Models\Incident\Incident;
use App\Models\Request\Request;
use App\Models\Incident\IncidentOnHoldReason;
use App\Models\Incident\IncidentStatus;
use App\Models\Request\RequestOnHoldReason;
use App\Models\Request\RequestStatus;
use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    public function test_it_logs_incident_created_event()
    {
        $incident = Incident::factory()->create();
        $activity = $incident->activities->first();

        $this->assertEquals('created', $activity->event);
    }

    public function test_it_logs_request_created_event()
    {
        $request = Request::factory()->create();
        $activity = $request->activities->first();

        $this->assertEquals('created', $activity->event);
    }

    public function test_it_logs_incident_status_updated_event()
    {
        $incident = Incident::factory(['status_id' => IncidentStatus::OPEN])->create();
        $incident->status_id = IncidentStatus::IN_PROGRESS;
        $incident->save();

        $activity = $incident->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('In Progress', $activity->changes['attributes']['status.name']);
        $this->assertEquals('Open', $activity->changes['old']['status.name']);
    }

    public function test_it_logs_request_status_updated_event()
    {
        $request = Request::factory(['status_id' => RequestStatus::OPEN])->create();
        $request->status_id = RequestStatus::IN_PROGRESS;
        $request->save();

        $activity = $request->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('In Progress', $activity->changes['attributes']['status.name']);
        $this->assertEquals('Open', $activity->changes['old']['status.name']);
    }

    public function test_it_logs_incident_on_hold_reason_updated_event()
    {
        $incident = Incident::factory()->create();
        $incident->status_id = IncidentStatus::ON_HOLD;
        $incident->on_hold_reason_id = IncidentOnHoldReason::CALLER_RESPONSE;
        $incident->save();

        $activity = $incident->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('Caller Response', $activity->changes['attributes']['onHoldReason.name']);
        $this->assertEquals(null, $activity->changes['old']['onHoldReason.name']);
    }

    public function test_it_logs_request_on_hold_reason_updated_event()
    {
        $request = Request::factory()->create();
        $request->status_id = RequestStatus::ON_HOLD;
        $request->on_hold_reason_id = RequestOnHoldReason::CALLER_RESPONSE;
        $request->save();

        $activity = $request->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('Caller Response', $activity->changes['attributes']['onHoldReason.name']);
        $this->assertEquals(null, $activity->changes['old']['onHoldReason.name']);
    }

    public function test_it_logs_incident_priority_updated_event()
    {
        $incident = Incident::factory()->create();
        $initialPriority = $incident->priority;
        $incident->priority = 3;
        $incident->save();

        $activity = $incident->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals(3, $activity->changes['attributes']['priority']);
        $this->assertEquals($initialPriority, $activity->changes['old']['priority']);
    }

    public function test_it_logs_request_priority_updated_event()
    {
        $request = Request::factory(['priority' => Ticket::DEFAULT_PRIORITY])->create();
        $initialPriority = $request->priority;
        $request->priority = 3;
        $request->save();

        $activity = $request->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals(3, $activity->changes['attributes']['priority']);
        $this->assertEquals($initialPriority, $activity->changes['old']['priority']);
    }

    public function test_it_logs_incident_group_updated_event()
    {
        $incident = Incident::factory(['group_id' => Group::SERVICE_DESK])->create();
        $incident->group_id = Group::LOCAL_6445_NEW_YORK;
        $incident->save();

        $activity = $incident->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('LOCAL-6445-NEW-YORK', $activity->changes['attributes']['group.name']);
        $this->assertEquals('SERVICE-DESK', $activity->changes['old']['group.name']);
    }

    public function test_it_logs_request_group_updated_event()
    {
        $request = Request::factory(['group_id' => Group::SERVICE_DESK])->create();
        $request->group_id = Group::LOCAL_6445_NEW_YORK;
        $request->save();

        $activity = $request->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('LOCAL-6445-NEW-YORK', $activity->changes['attributes']['group.name']);
        $this->assertEquals('SERVICE-DESK', $activity->changes['old']['group.name']);
    }

    public function test_it_logs_incident_resolver_updated_event()
    {
        $resolver = User::factory(['name' => 'Average Joe'])->resolver()->create();
        $incident = Incident::factory()->create();
        $incident->resolver_id = $resolver->id;
        $incident->save();

        $activity = $incident->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('Average Joe', $activity->changes['attributes']['resolver.name']);
        $this->assertEquals(null, $activity->changes['old']['resolver.name']);
    }

    public function test_it_logs_request_resolver_updated_event()
    {
        $resolver = User::factory(['name' => 'Average Joe'])->resolver()->create();
        $request = Request::factory()->create();
        $request->resolver_id = $resolver->id;
        $request->save();

        $activity = $request->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('Average Joe', $activity->changes['attributes']['resolver.name']);
        $this->assertEquals(null, $activity->changes['old']['resolver.name']);
    }
}
