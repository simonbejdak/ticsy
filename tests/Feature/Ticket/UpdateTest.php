<?php

namespace Tests\Feature\Ticket;

use App\Livewire\TicketForm;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
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

    public function test_it_updates_ticket_when_correct_data_submitted()
    {
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();
        $status = TicketConfiguration::STATUSES['in_progress'];
        $priority = TicketConfiguration::DEFAULT_PRIORITY - 1;

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', $status)
            ->set('priority', $priority)
            ->set('resolver', $resolver->id)
            ->call('update');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'priority' => $priority,
            'status_id' => $status,
            'resolver_id' => $resolver->id,
        ]);
    }

    public function test_ticket_priority_cannot_be_changed_when_status_is_resolved(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['status_id' => TicketConfiguration::STATUSES['resolved']])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('priority', TicketConfiguration::DEFAULT_PRIORITY - 1)
            ->call('update')
            ->assertForbidden();
    }

    public function test_ticket_status_cannot_be_changed_when_status_is_resolved(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['status_id' => TicketConfiguration::STATUSES['resolved']])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', TicketConfiguration::DEFAULT_STATUS)
            ->call('update')
            ->assertForbidden();
    }

    public function test_ticket_resolver_cannot_be_changed_when_status_is_resolved(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['status_id' => TicketConfiguration::STATUSES['resolved']])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('resolver', $resolver)
            ->call('update')
            ->assertForbidden();
    }
}
