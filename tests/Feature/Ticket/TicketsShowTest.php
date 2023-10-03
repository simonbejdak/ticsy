<?php


namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketsShowTest extends TestCase
{
    use RefreshDatabase;
    function test_tickets_show_redirects_guests_to_login_page()
    {
        $response = $this->get(route('tickets.show', 1));

        $response->assertRedirectToRoute('login');
    }

    function test_tickets_show_errors_to_403_to_unauthorized_users()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('tickets.show', 1));

        $response->assertForbidden();
    }

    function test_tickets_show_displays_ticket_to_authorized_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tested = [
            'category' => Ticket::CATEGORIES['network'],
            'description' => 'Ticket Description 1',
        ];

        $ticket = Ticket::factory([
            'user_id' => $user,
            'category_id' => $tested['category'],
            'description' => $tested['description'],
        ])->create();

        $response = $this->get(route('tickets.show', $ticket));
        $response->assertSuccessful();
        $response->assertSee($tested['category']);
        $response->assertSee($tested['description']);
    }
}
