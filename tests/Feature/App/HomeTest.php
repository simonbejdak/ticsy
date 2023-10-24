<?php

namespace App;

use App\Http\Controllers\HomeController;
use App\Models\Ticket;
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

    public function test_it_does_not_display_recent_tickets_when_guest()
    {
        Ticket::factory(10, ['description' => 'Ticket Description'])->create();

        $response = $this->get(route('home'));
        $response->assertSuccessful();
        $response->assertDontSee('Recent tickets you have already created:');
        $response->assertDontSee('Ticket Description');
    }

    public function test_it_does_not_display_recent_tickets_when_user_has_no_tickets()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('home'));

        $response->assertSuccessful();
        $response->assertDontSee('Recent tickets you have already created:');
    }

    public function test_it_displays_recent_tickets_when_user_has_tickets()
    {
        $user = User::factory()->create();
        Ticket::factory(HomeController::RECENT_TICKETS_COUNT, [
            'user_id' => $user,
            'description' => 'Ticket Description',
        ])->create();

        $this->actingAs($user);
        $response = $this->get(route('home'));

        $response->assertSee('Recent tickets you have already created');
        $response->assertSee('Ticket Description');
    }

    public function test_it_displays_correct_number_of_recent_tickets()
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= HomeController::RECENT_TICKETS_COUNT + 1; $i++){
            Ticket::factory([
                'user_id' => $user,
                'description' => 'Ticket Description ' . $i,
            ])->create();
        }

        $this->actingAs($user);
        $response = $this->get(route('home'));

        for ($i = 1; $i <= HomeController::RECENT_TICKETS_COUNT; $i++){
            $response->assertSee('Ticket Description ' . $i);
        }

        $response->assertDontSee('Ticket Description ' . HomeController::RECENT_TICKETS_COUNT + 1);
    }
}
