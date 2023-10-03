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
        Ticket::factory()->create();
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('tickets.edit', 1));

        $response->assertForbidden();
    }
}
