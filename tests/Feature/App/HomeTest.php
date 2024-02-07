<?php

namespace App;

use App\Http\Controllers\HomeController;
use App\Models\Incident;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads_successfully()
    {
        $response = $this->get(route('home'));
        $response->assertSuccessful();
    }

    public function test_it_does_not_display_recent_incidents_when_guest()
    {
        Incident::factory(10, ['description' => 'Incident Description'])->create();

        $response = $this->get(route('home'));
        $response->assertSuccessful();
        $response->assertDontSee('Recent incidents you have already created:');
        $response->assertDontSee('Incident Description');
    }

    public function test_it_does_not_display_recent_tickets_when_user_has_no_tickets()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('home'));

        $response->assertSuccessful();
        $response->assertDontSee('Recent incidents you have already created:');
    }

    public function test_it_displays_recent_incidents_when_user_has_incident()
    {
        $caller = User::factory()->create();
        Incident::factory(HomeController::RECENT_INCIDENTS_COUNT, [
            'caller_id' => $caller,
            'description' => 'Incident Description',
        ])->create();

        $this->actingAs($caller);
        $response = $this->get(route('home'));

        $response->assertSee('Your recent incidents:');
        $response->assertSee('Incident Description');
    }

    public function test_it_displays_correct_number_of_recent_incidents()
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= HomeController::RECENT_INCIDENTS_COUNT; $i++){
            Incident::factory([
                'caller_id' => $user,
                'description' => 'Incident Description ' . $i,
            ])->create();
        }


        $this->actingAs($user);
        $response = $this->get(route('home'));

        for ($i = 1; $i <= HomeController::RECENT_INCIDENTS_COUNT; $i++){
            $response->assertSee('Incident Description ' . $i);
        }

        $response->assertDontSee('Incident ' . HomeController::RECENT_INCIDENTS_COUNT + 1);
    }

    public function test_it_displays_correct_number_of_recent_requests()
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= HomeController::RECENT_REQUESTS_COUNT; $i++){
            Request::factory([
                'caller_id' => $user,
                'description' => 'Request Description ' . $i,
            ])->create();
        }


        $this->actingAs($user);
        $response = $this->get(route('home'));

        for ($i = 1; $i <= HomeController::RECENT_REQUESTS_COUNT; $i++){
            $response->assertSee('Request Description ' . $i);
        }

        $response->assertDontSee('Request Description ' . HomeController::RECENT_REQUESTS_COUNT + 1);
    }
}
