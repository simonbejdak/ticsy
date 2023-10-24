<?php

namespace Tests\Feature\Ticket;

use App\Livewire\TicketForm;
use App\Models\Group;
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
            ->assertForbidden();
    }

    public function test_resolver_can_set_status()
    {
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', TicketConfiguration::STATUSES['in_progress'])
            ->call('save');

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
            ->call('save')
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
            ->assertForbidden();
    }

    public function test_resolver_user_can_set_resolver()
    {
        $user = User::factory()->create()->assignRole('resolver');
        $group = Group::findOrFail(Group::DEFAULT);
        $resolver = User::factory()->hasAttached($group)->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($user);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('resolver', $resolver->id)
            ->call('save');

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
            ->call('save');

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
            ->assertForbidden();

        $ticket = Ticket::findOrFail($ticket->id);
        $this->assertEquals(4, $ticket->priority);
    }

    public function test_it_updates_ticket_when_correct_data_submitted()
    {
        $group = Group::GROUPS['LOCAL-6445-NEW-YORK'];
        $resolver = User::factory()->create()->assignRole('resolver');
        $resolver->groups()->attach($group);
        $ticket = Ticket::factory()->create();
        $status = TicketConfiguration::STATUSES['in_progress'];
        $priority = TicketConfiguration::DEFAULT_PRIORITY - 1;

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', $status)
            ->set('priority', $priority)
            ->set('group', $group)
            ->set('resolver', $resolver->id)
            ->call('save');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'priority' => $priority,
            'status_id' => $status,
            'group_id' => $group,
            'resolver_id' => $resolver->id,
        ]);
    }

    public function test_ticket_priority_cannot_be_changed_when_status_is_resolved(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory([
            'priority' => TicketConfiguration::DEFAULT_PRIORITY,
            'status_id' => TicketConfiguration::STATUSES['resolved']
        ])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('priority', TicketConfiguration::DEFAULT_PRIORITY - 1)
            ->assertForbidden();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'priority' => TicketConfiguration::DEFAULT_PRIORITY,
        ]);
    }

    public function test_ticket_status_can_be_changed_when_status_is_resolved(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['status_id' => TicketConfiguration::STATUSES['resolved']])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', TicketConfiguration::DEFAULT_STATUS)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tickets', [
           'id' => $ticket->id,
           'status_id' => TicketConfiguration::DEFAULT_STATUS,
        ]);
    }

    public function test_ticket_resolver_cannot_be_changed_when_status_is_resolved(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory([
            'status_id' => TicketConfiguration::STATUSES['resolved'],
            'resolver_id' => null,
        ])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('resolver', $resolver)
            ->assertForbidden();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'resolver_id' => null,
        ]);
    }

    public function test_ticket_priority_cannot_be_changed_when_status_is_cancelled(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['status_id' => TicketConfiguration::STATUSES['cancelled']])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('priority', TicketConfiguration::DEFAULT_PRIORITY - 1)
            ->assertForbidden();
    }

    public function test_ticket_status_cannot_be_changed_when_status_is_cancelled(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['status_id' => TicketConfiguration::STATUSES['cancelled']])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('status', TicketConfiguration::DEFAULT_STATUS)
            ->assertForbidden();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status_id' => TicketConfiguration::STATUSES['cancelled'],
        ]);
    }

    public function test_ticket_resolver_cannot_be_changed_when_status_is_cancelled(){
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['status_id' => TicketConfiguration::STATUSES['cancelled']])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('resolver', $resolver)
            ->assertForbidden();
    }

    public function test_resolver_field_lists_resolvers_based_on_selected_group()
    {
        $resolverOne = User::factory(['name' => 'John Doe'])->create()->assignRole('resolver');
        $resolverTwo = User::factory(['name' => 'Joey Rogan'])->create()->assignRole('resolver');
        $resolverThree = User::factory(['name' => 'Fred Flinstone'])->create()->assignRole('resolver');

        $groupOne = Group::factory(['name' => 'SERVICE-DESK'])->create();
        $groupOne->resolvers()->attach($resolverOne);
        $groupOne->resolvers()->attach($resolverTwo);

        $groupTwo = Group::factory(['name' => 'LOCAL-6445-NEW-YORK'])->create();
        $groupTwo->resolvers()->attach($resolverThree);


        $ticket = Ticket::factory(['group_id' => $groupOne])->create();

        Livewire::actingAs($resolverOne);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->assertSuccessful()
            ->set('group', $groupOne->id)
            ->assertSee('John Doe')
            ->assertSee('Joey Rogan')
            ->assertDontSee('Fred Flinstone');

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->assertSuccessful()
            ->set('group', $groupTwo->id)
            ->assertDontSee('John Doe')
            ->assertDontSee('Joey Rogan')
            ->assertSee('Fred Flinstone');
    }

    public function test_resolver_from_not_selected_group_cannot_be_assigned_to_the_ticket_as_resolver()
    {
        $resolverOne = User::factory()->create()->assignRole('resolver');
        $resolverTwo = User::factory()->create()->assignRole('resolver');

        $groupOne = Group::factory()->create();
        $groupOne->resolvers()->attach($resolverOne);

        $groupTwo = Group::factory()->create();
        $groupTwo->resolvers()->attach($resolverTwo);

        $ticket = Ticket::factory(['group_id' => $groupOne])->create();

        Livewire::actingAs($resolverOne);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->assertSuccessful()
            ->set('resolver', $resolverTwo->id)
            ->assertForbidden();
    }

    public function test_selected_resolver_is_empty_when_resolver_group_changes()
    {
        $resolverOne = User::factory()->create()->assignRole('resolver');
        $groupOne = Group::findOrFail(Group::GROUPS['SERVICE-DESK']);
        $resolverOne->groups()->attach($groupOne);

        $resolverTwo = User::factory()->create()->assignRole('resolver');
        $groupTwo = (Group::findOrFail(Group::GROUPS['LOCAL-6445-NEW-YORK']));
        $resolverTwo->groups()->attach($groupTwo);

        $ticket = Ticket::factory(['group_id' => $groupOne])->create();

        Livewire::actingAs($resolverOne);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('resolver', $resolverOne->id)
            ->call('save');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'group_id' => $groupOne->id,
            'resolver_id' => $resolverOne->id,
        ]);

        Livewire::test(TicketForm::class, ['ticket' => $ticket])
            ->set('group', $groupTwo->id)
            ->call('save');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'group_id' => $groupTwo->id,
            'resolver_id' => null,
        ]);
    }
}
