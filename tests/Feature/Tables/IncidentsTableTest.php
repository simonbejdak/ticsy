<?php

namespace Tables;

use App\Enums\Priority;
use App\Livewire\Tables\IncidentsTable;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
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

    /** @test */
    function it_renders_column_headers_in_correct_order()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.incidents'));

        $response->assertSeeInOrder(['Number', 'Caller', 'Resolver', 'Status', 'Priority']);
    }

    /** @test */
    function it_renders_incident_data_in_correct_order()
    {
        $incidents = Incident::factory(3)->withResolver()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.incidents'));
        foreach ($incidents as $incident){
            $response->assertSeeInOrder([
                $incident->id,
                $incident->caller->name,
                $incident->resolver->name,
                $incident->status->value,
                $incident->priority->value,
            ]);
        }
    }

    /** @test */
    function it_renders_incidents_in_descending_order_by_default()
    {
        $incident_one = Incident::factory()->create();
        $incident_two = Incident::factory()->create();
        $incident_three = Incident::factory()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.incidents'));

        $response->assertSeeInOrder([$incident_three->caller->name, $incident_two->caller->name, $incident_one->caller->name]);
    }

    /** @test */
    function it_renders_incidents_in_ascending_order_if_wire_click_on_number_header()
    {
        $incident_one = Incident::factory()->create();
        $incident_two = Incident::factory()->create();
        $incident_three = Incident::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$incident_one->caller->name, $incident_two->caller->name, $incident_three->caller->name]);
    }

    /** @test */
    function it_renders_incidents_in_descending_order_if_wire_click_twice_on_number_header()
    {
        $incident_one = Incident::factory()->create();
        $incident_two = Incident::factory()->create();
        $incident_three = Incident::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$incident_three->caller->name, $incident_two->caller->name, $incident_one->caller->name]);
    }

    /** @test */
    function it_renders_incidents_in_ascending_order_if_wire_click_on_priority_header()
    {
        $incident_one = Incident::factory()->create();
        $incident_two = Incident::factory()->create();
        $incident_three = Incident::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$incident_three->caller->name, $incident_two->caller->name, $incident_one->caller->name]);
    }

    /** @test */
    function it_renders_incidents_in_ascending_order_based_on_priority_if_wire_click_on_priority_header()
    {
        $resolver = User::factory()->resolver()->create();
        $incident_one = Incident::factory(['priority' => Priority::ONE])->create();
        $incident_two = Incident::factory(['priority' => Priority::TWO])->create();
        $incident_three = Incident::factory(['priority' => Priority::THREE])->create();
        $incident_four = Incident::factory(['priority' => Priority::FOUR])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->call('columnHeaderClicked', 'priority.value')
            ->assertSeeInOrder([Priority::ONE->value, Priority::TWO->value, Priority::THREE->value, Priority::FOUR->value]);
    }

    /** @test */
    function it_filters_incidents_based_on_id_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Incident::factory(['id' => 1234])->create();
        Incident::factory(['id' => 5678])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('1234')
            ->assertSee('5678')
            ->set('searchCases.id', 1234)
            ->assertSee('1234')
            ->assertDontSee('5678')
            ->set('searchCases.id', 5678)
            ->assertDontSee('1234')
            ->assertSee('5678')
            ->set('searchCases.id', 'trt')
            ->assertDontSee('1234')
            ->assertDontSee('5678');
    }
}

