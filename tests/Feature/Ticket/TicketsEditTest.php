<?php


namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketsEditTest extends TestCase
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

    function test_it_authorizes_caller_and_resolver_to_view(){
        $user = User::factory()->create();
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();

        $this->actingAs($resolver);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();
    }

    function test_it_displays_priority_select_as_greyed_out_when_user_cannot_change_priority(){
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSee('w-full rounded-lg border border-gray-300 bg-gray-200 px-1 py-2');
    }

    function test_it_does_not_display_priority_select_as_greyed_out_when_user_can_change_priority(){
        $user = User::factory()->canChangePriority()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertDontSee('w-full rounded-lg border border-gray-300 bg-gray-200 px-1 py-2');
        $response->assertSee('w-full rounded-lg border border-gray-300 bg-white hover:cursor-pointer px-1 py-2');
    }
}
