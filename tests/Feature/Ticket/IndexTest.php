<?php


namespace Tests\Feature\Ticket;

use App\Http\Controllers\TicketsController;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    function testTicketsIndexRedirectsGuestsToLoginPage()
    {
        $response = $this->get(route('tickets.index'));

        $response->assertRedirectToRoute('login');
    }

    function testTicketsIndexDisplaysTicketsCorrectly()
    {
        $tested = [
            'user_name' => 'John',
            'resolver_name' => 'Thomas',
            'description' => 'Ticket 1 description',
        ];

        $user = User::factory([
            'name' => $tested['user_name']
        ])->create();

        $resolver = User::factory([
            'name' => $tested['resolver_name']
        ])->create()->assignRole('resolver');

        $this->actingAs($user);

        Ticket::factory([
            'description' => $tested['description'],
            'user_id' => $user,
            'resolver_id' => $resolver,
        ])->create();

        $response = $this->get(route('tickets.index'));
        $response->assertSuccessful();
        $response->assertSee($tested['description']);
        $response->assertSee($tested['user_name']);
        $response->assertSee($tested['resolver_name']);
    }

    function test_tickets_index_pagination_displays_correct_number_of_tickets()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Ticket::factory(TicketsController::DEFAULT_PAGINATION, [
            'user_id' => $user,
        ])->create();

        Ticket::factory([
            'description' => 'This ticket is supposed to be on second pagination page',
            'user_id' => $user,
        ])->create();

        $response = $this->get(route('tickets.index', ['page' => 1]));
        $response->assertSuccessful();
        $response->assertDontSee('This ticket is supposed to be on second pagination page');

        $response = $this->get(route('tickets.index', ['page' => 2]));
        $response->assertSuccessful();
        $response->assertSee('This ticket is supposed to be on second pagination page');
    }
}
