<?php

namespace Tests\Feature\Ticket;

use App\Livewire\TicketForm;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SetResolverTest extends TestCase
{
    public function test_guest_is_redirected_to_login_page()
    {
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        $response = $this->patch(route('tickets.set-resolver', $ticket), [
            'resolver' => $resolver
        ]);

        $response->assertRedirectToRoute('login');
    }

    public function test_non_resolver_user_cannot_set_resolver()
    {
        $user = User::factory()->create();
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($user);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('resolver', $resolver->id)
            ->call('update')
            ->assertForbidden();
    }

    public function test_resolver_user_can_set_resolver()
    {
        $user = User::factory()->create()->assignRole('resolver');
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($user);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('resolver', $resolver->id)
            ->call('update');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'resolver_id' => $resolver->id,
        ]);
    }
}
