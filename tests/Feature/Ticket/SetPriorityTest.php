<?php

namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;

class SetPriorityTest extends TestCase
{
    function test_user_can_change_priority_with_permission()
    {
        $resolver = User::factory()->canChangePriority()->create();

        $ticket = Ticket::factory(['priority' => 4])->create();

        $this->actingAs($resolver);

        $result = $this->patch(route('tickets.set-priority', $ticket), ['priority' => 2]);

        $result->assertRedirectToRoute('tickets.edit', $ticket);

        $ticket = Ticket::findOrFail($ticket->id);
        $this->assertEquals(2, $ticket->priority);
    }

    function test_user_cannot_change_priority_without_permission()
    {
        $resolver = User::factory()->create();

        $ticket = Ticket::factory(['priority' => 4])->create();

        $this->actingAs($resolver);

        $response = $this->patch(route('tickets.set-priority', $ticket), ['priority' => 2]);

        $response->assertForbidden();

        $ticket = Ticket::findOrFail($ticket->id);
        $this->assertEquals(4, $ticket->priority);
    }
}
