<?php

namespace ResolverPanel;

use App\Enums\Priority;
use App\Enums\Tab;
use App\Helpers\Table\Table;
use App\Livewire\Tables\IncidentsTable;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_redirects_guest_to_login_page()
    {
        $response = $this->get(route('resolver-panel.incidents'));

        $response->assertRedirectToRoute('login');
    }

    /** @test */
    function it_redirects_user_to_home_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('resolver-panel.incidents'));

        $response->assertRedirectToRoute('home');
    }

    /** @test */
    function it_loads_successfully_to_resolver()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.incidents'));

        $response->assertSuccessful();
    }

    /** @test */
    function it_loads_successfully_to_manager()
    {
        $manager = User::factory()->manager()->create();

        $this->actingAs($manager);
        $response = $this->get(route('resolver-panel.incidents'));

        $response->assertSuccessful();
    }
}

