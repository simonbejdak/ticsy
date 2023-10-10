<?php

namespace Tests\Feature\Ticket;

use App\Livewire\TicketForm;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SetPriorityTest extends TestCase
{
    function test_user_can_change_priority_with_permission()
    {
        $resolver = User::factory()->create()->givePermissionTo('set_priority');

        $ticket = Ticket::factory(['priority' => 4])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('priority', 2)
            ->call('update');

        $ticket = Ticket::findOrFail($ticket->id);
        $this->assertEquals(2, $ticket->priority);
    }

    function test_user_cannot_change_priority_without_permission()
    {
        $user = User::factory()->create();

        $ticket = Ticket::factory(['priority' => 4])->create();

        Livewire::actingAs($user);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('priority', 2)
            ->call('update')
            ->assertForbidden();

        $ticket = Ticket::findOrFail($ticket->id);
        $this->assertEquals(4, $ticket->priority);
    }
}
