<?php


namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
