<?php

namespace Tests\Feature\Ticket;

use App\Livewire\TicketForm;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SetStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_redirects_guest_to_login_page()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->patch(route('tickets.set-status', $ticket), [
            'status' => TicketConfiguration::STATUSES['in_progress'],
        ]);

        $response->assertRedirectToRoute('login');
    }

    public function test_non_resolver_user_cannot_set_status()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($user);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', TicketConfiguration::STATUSES['in_progress'])
            ->call('update')
            ->assertForbidden();
    }

    public function test_resolver_can_set_status()
    {
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', TicketConfiguration::STATUSES['in_progress'])
            ->call('update');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status_id' => TicketConfiguration::STATUSES['in_progress'],
        ]);
    }

    public function test_it_fails_validation_when_unknown_status_is_set()
    {
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', count(TicketConfiguration::STATUSES) + 1)
            ->call('update')
            ->assertHasErrors(['status' => 'max']);
    }
}
