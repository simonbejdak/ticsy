<?php


use App\Models\Category;
use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\User;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    public function test_it_logs_ticket_created_event()
    {
        $ticket = Ticket::factory()->create();
        $activity = $ticket->activities->first();

        $this->assertEquals('created', $activity->event);
    }

    public function test_it_logs_ticket_status_updated_event()
    {
        $ticket = Ticket::factory(['status_id' => Status::OPEN])->create();
        $ticket->status_id = Status::IN_PROGRESS;
        $ticket->save();

        $activity = $ticket->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals(Status::IN_PROGRESS, $activity->changes['attributes']['status']['id']);
        $this->assertEquals(Status::OPEN, $activity->changes['old']['status']['id']);
    }

    public function test_it_logs_ticket_on_hold_reason_updated_event()
    {
        $ticket = Ticket::factory()->create();
        $ticket->status_id = Status::ON_HOLD;
        $ticket->on_hold_reason_id = OnHoldReason::CALLER_RESPONSE;
        $ticket->save();

        $activity = $ticket->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals(OnHoldReason::CALLER_RESPONSE, $activity->changes['attributes']['onHoldReason']['id']);
        $this->assertEquals(null, $activity->changes['old']['onHoldReason']);
    }

    public function test_it_logs_ticket_priority_updated_event()
    {
        $ticket = Ticket::factory(['priority' => Ticket::DEFAULT_PRIORITY])->create();
        $ticket->priority = 3;
        $ticket->save();

        $activity = $ticket->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals(3, $activity->changes['attributes']['priority']);
        $this->assertEquals(Ticket::DEFAULT_PRIORITY, $activity->changes['old']['priority']);
    }

    public function test_it_logs_ticket_group_updated_event()
    {
        $ticket = Ticket::factory(['group_id' => Group::SERVICE_DESK])->create();
        $ticket->group_id = Group::LOCAL_6445_NEW_YORK;
        $ticket->save();

        $activity = $ticket->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals(Group::LOCAL_6445_NEW_YORK, $activity->changes['attributes']['group']['id']);
        $this->assertEquals(Group::SERVICE_DESK, $activity->changes['old']['group']['id']);
    }

    public function test_it_logs_ticket_resolver_updated_event()
    {
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->create();
        $ticket->resolver_id = $resolver->id;
        $ticket->save();

        $activity = $ticket->activities->last();

        $this->assertEquals('updated', $activity->event);
        $this->assertEquals($resolver->id, $activity->changes['attributes']['resolver']['id']);
        $this->assertEquals(null, $activity->changes['old']['resolver']);
    }
}
