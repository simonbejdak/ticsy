<?php


namespace Tests\Feature\Ticket;

use App\Livewire\TicketForm;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
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
        $type = Type::factory(['name' => 'incident'])->create();
        $category = Category::factory(['name' => 'network'])->create();
        $group = Group::factory(['name' => 'LOCAL-6380-NEW-JERSEY'])->create();
        $resolver = User::factory(['name' => 'John Doe'])->resolver()->create();
        $resolver->groups()->attach($group);
        $status = Status::factory(['name' => 'open'])->create();

        $user = User::factory()->create();
        $ticket = Ticket::factory([
            'type_id' => $type,
            'category_id' => $category,
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
            ->test(TicketForm::class, ['ticket' => $ticket])
            ->set('group', count(Group::GROUPS) + 1)
            ->assertSee('The group field must not be greater than '. count(Group::GROUPS) .'.');
    }
}
