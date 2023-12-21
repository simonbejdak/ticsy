<?php


namespace Tests\Feature\Incident;

use App\Http\Controllers\IncidentsController;
use App\Models\Incident;
use App\Models\Resolver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    function testTicketsIndexRedirectsGuestsToLoginPage()
    {
        $response = $this->get(route('incidents.index'));

        $response->assertRedirectToRoute('login');
    }

    function test_incident_index_displays_incidents_correctly()
    {
        $user = User::factory(['name' => 'John Doe'])->create();
        $resolver = User::factory(['name' => 'Jeff Wing'])->resolver()->create();
        Incident::factory([
            'description' => 'Incident Description',
            'caller_id' => $user,
            'resolver_id' => $resolver,
        ])->create();

        $this->actingAs($user);
        $response = $this->get(route('incidents.index'));

        $response->assertSuccessful();
        $response->assertSee('John Doe');
        $response->assertSee('Jeff Wing');
        $response->assertSee('Incident Description');
    }

    function test_tickets_index_pagination_displays_correct_number_of_tickets()
    {
        $user = User::factory()->create();

        Incident::factory([
            'description' => 'This ticket is supposed to be on the second pagination page',
            'caller_id' => $user,
        ])->create();

        Incident::factory(IncidentsController::DEFAULT_PAGINATION, [
            'caller_id' => $user,
        ])->create();

        $this->actingAs($user);

        $response = $this->get(route('incidents.index', ['page' => 1]));
        $response->assertSuccessful();
        $response->assertDontSee('This ticket is supposed to be on the second pagination page');

        $response = $this->get(route('incidents.index', ['page' => 2]));
        $response->assertSuccessful();
        $response->assertSee('This ticket is supposed to be on the second pagination page');
    }
}
