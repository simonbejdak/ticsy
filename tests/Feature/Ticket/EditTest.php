<?php


namespace Tests\Feature\Ticket;

use App\Livewire\TicketEditForm;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Item;
use App\Models\OnHoldReason;
use App\Models\Resolver;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;
    function test_it_redirect_guests_to_login_page()
    {
        $response = $this->get(route('tickets.edit', 1));

        $response->assertRedirectToRoute('login');
    }

    function test_it_errors_to_403_to_unauthorized_users()
    {
        $ticket = Ticket::factory()->create();

        $this->actingAs(User::factory()->create());
        $response = $this->get(route('tickets.edit', $ticket));

        $response->assertForbidden();
    }

    function test_it_authorizes_caller_to_view(){
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();
    }

    function test_it_authorizes_resolver_to_view(){
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();
    }

    public function test_it_displays_ticket_data()
    {
        $type = Type::firstOrFail();
        $category = Category::firstOrFail();
        $item = Item::firstOrFail();
        $group = Group::firstOrFail();
        $status = Status::firstOrFail();

        $resolver = User::factory(['name' => 'John Doe'])->resolver(true)->create();

        $user = User::factory()->create();
        $ticket = Ticket::factory([
            'type_id' => $type,
            'category_id' => $category,
            'item_id' => $item,
            'group_id' => $group,
            'resolver_id' => $resolver,
            'status_id' => $status,
            'user_id' => $user,
        ])->create();


        $this->actingAs($user);

        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();
        $response->assertSee($type->name);
        $response->assertSee($category->name);
        $response->assertSee($item->name);
        $response->assertSee($group->name);
        $response->assertSee($resolver->name);
        $response->assertSee($status->name);
    }

    public function test_it_displays_comments()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();
        Comment::factory([
            'body' => 'Comment Body',
            'ticket_id' => $ticket,
            'user_id' => $user,
        ])->create();

        $this->actingAs($user);
        $response = $this->get(route('tickets.edit', $ticket));

        $response->assertSuccessful();
        $response->assertSee('Comment Body');
    }

    public function test_it_shows_validation_error_when_unknown_group_is_selected()
    {
        $ticket = Ticket::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TicketEditForm::class, ['ticket' => $ticket])
            ->set('group', Group::count() + 1)
            ->call('save')
            ->assertSee('The group field must not be greater than '. Group::count() .'.');
    }

    public function test_on_hold_reason_field_is_hidden_when_status_is_not_on_hold()
    {
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->inProgress()->create();

        Livewire::actingAs($resolver)
            ->test(TicketEditForm::class, ['ticket' => $ticket])
            ->assertDontSee('On hold reason');
    }
    public function test_on_hold_reason_field_is_shown_when_status_is_on_hold()
    {
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory(['on_hold_reason_id' => OnHoldReason::WAITING_FOR_VENDOR])
            ->onHold()->create();

        Livewire::actingAs($resolver)
            ->test(TicketEditForm::class, ['ticket' => $ticket])
            ->assertSee('On hold reason');
    }

    public function test_status_can_be_set_to_cancelled_if_previous_status_is_different()
    {
        $ticket = Ticket::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TicketEditForm::class, ['ticket' => $ticket])
            ->set('status', Status::CANCELLED)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status_id' => Status::CANCELLED,
        ]);
    }

    public function test_it_returns_validation_error_if_user_with_no_permission_assigns_priority_one_to_a_ticket()
    {
        // resolver does not have a permisssion to set priority one
        $user = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($user)
            ->test(TicketEditForm::class, ['ticket' => $ticket])
            ->set('priority', 1)
            ->call('save')
            ->assertHasErrors(['priority' => 'min']);
    }

    public function test_it_does_not_return_validation_error_if_user_with_permission_assigns_priority_one_to_a_ticket()
    {
        // manager has a permisssion to set priority one
        $user = User::factory()->manager()->create();
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($user)
            ->test(TicketEditForm::class, ['ticket' => $ticket])
            ->set('priority', 1)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tickets', [
           'id' => $ticket->id,
           'priority' => 1,
        ]);
    }

    public function test_it_allows_user_with_permission_to_set_priority_one_to_also_set_lower_priorities()
    {
        // manager has a permisssion to set priority one
        $user = User::factory()->manager()->create();
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($user)
            ->test(TicketEditForm::class, ['ticket' => $ticket])
            ->set('priority', 2)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'priority' => 2,
        ]);
    }
}
