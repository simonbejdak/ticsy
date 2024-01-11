<?php


use App\Models\Group;
use App\Models\Incident;
use App\Enums\OnHoldReason;
use App\Models\Request;
use App\Enums\Status;
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
        $incident = Incident::factory(['status' => Status::OPEN])->create();
        $incident->status = Status::IN_PROGRESS;
        $incident->save();

        $activity = $incident->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('In Progress', $activity->changes['attributes']['status']);
        $this->assertEquals('Open', $activity->changes['old']['status']);
    }

    public function test_it_logs_request_status_updated_event()
    {
        $request = Request::factory(['status' => Status::OPEN])->create();
        $request->status = Status::IN_PROGRESS;
        $request->save();

        $activity = $request->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('In Progress', $activity->changes['attributes']['status']);
        $this->assertEquals('Open', $activity->changes['old']['status']);
    }

    public function test_it_logs_incident_on_hold_reason_updated_event()
    {
        $incident = Incident::factory()->create();
        $incident->status = Status::ON_HOLD;
        $incident->on_hold_reason = OnHoldReason::CALLER_RESPONSE;
        $incident->save();

        $activity = $incident->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('Caller Response', $activity->changes['attributes']['on_hold_reason']);
        $this->assertEquals(null, $activity->changes['old']['on_hold_reason']);
    }

    public function test_it_logs_request_on_hold_reason_updated_event()
    {
        $request = Request::factory()->create();
        $request->status = Status::ON_HOLD;
        $request->on_hold_reason = OnHoldReason::CALLER_RESPONSE;
        $request->save();

        $activity = $request->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals('Caller Response', $activity->changes['attributes']['on_hold_reason']);
        $this->assertEquals(null, $activity->changes['old']['on_hold_reason']);
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
        $request = Request::factory(['priority' => Request::DEFAULT_PRIORITY])->create();
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
