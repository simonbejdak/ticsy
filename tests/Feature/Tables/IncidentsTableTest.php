<?php

namespace Tables;

use App\Enums\Priority;
use App\Livewire\Tables\IncidentsTable;
use App\Livewire\Tables\Table;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IncidentsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_renders_column_headers_in_correct_order()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.incidents'));

        $response->assertSeeInOrder(['Number', 'Description', 'Caller', 'Resolver', 'Status', 'Priority']);
    }

    /** @test */
    function it_renders_incident_data_in_correct_order()
    {
        $incidents = Incident::factory(3)->withResolver()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.incidents'));
        foreach ($incidents as $incident) {
            $response->assertSeeInOrder([
                $incident->id,
                $incident->description,
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
            ->set('searchCases.id', '1234')
            ->assertSee('1234')
            ->assertDontSee('5678')
            ->set('searchCases.id', '5678')
            ->assertDontSee('1234')
            ->assertSee('5678')
            ->set('searchCases.id', 'trt')
            ->assertDontSee('1234')
            ->assertDontSee('5678');
    }

    /** @test */
    function it_filters_incidents_based_on_description_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Incident::factory([
            'description' => 'My computer is not working'
        ])->create();
        Incident::factory([
            'description' => 'Server is down'
        ])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('My computer is not working')
            ->assertSee('Server is down')
            ->set('searchCases.description', 'My c')
            ->assertSee('My computer is not working')
            ->assertDontSee('Server is down')
            ->set('searchCases.description', 'Serv')
            ->assertDontSee('My computer is not working')
            ->assertSee('Server is down')
            ->set('searchCases.description', 'Applica')
            ->assertDontSee('My computer is not working')
            ->assertDontSee('Server is down')
            ->set('searchCases.description', '')
            ->assertSee('My computer is not working')
            ->assertSee('Server is down');
    }

    /** @test */
    function it_filters_incidents_based_on_caller_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Incident::factory([
            'caller_id' => User::factory(['name' => 'Eugen Slavic'])->create()
        ])->create();
        Incident::factory([
            'caller_id' => User::factory(['name' => 'Anastasia Yosling'])->create()
        ])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('Eugen Slavic')
            ->assertSee('Anastasia Yosling')
            ->set('searchCases.caller.name', 'Eug')
            ->assertSee('Eugen Slavic')
            ->assertDontSee('Anastasia Yosling')
            ->set('searchCases.caller.name', 'Anas')
            ->assertDontSee('Eugen Slavic')
            ->assertSee('Anastasia Yosling')
            ->set('searchCases.caller.name', 'Joe')
            ->assertDontSee('Eugen Slavic')
            ->assertDontSee('Anastasia Yosling');
    }

    /** @test */
    function it_filters_incidents_based_on_resolver_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Incident::factory([
            'resolver_id' => User::factory(['name' => 'Eugen Slavic'])->resolver()->create()
        ])->create();
        Incident::factory([
            'resolver_id' => User::factory(['name' => 'Anastasia Yosling'])->resolver()->create()
        ])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('Eugen Slavic')
            ->assertSee('Anastasia Yosling')
            ->set('searchCases.resolver.name', 'Eug')
            ->assertSee('Eugen Slavic')
            ->assertDontSee('Anastasia Yosling')
            ->set('searchCases.resolver.name', 'Anas')
            ->assertDontSee('Eugen Slavic')
            ->assertSee('Anastasia Yosling')
            ->set('searchCases.resolver.name', 'Joe')
            ->assertDontSee('Eugen Slavic')
            ->assertDontSee('Anastasia Yosling');
    }

    /** @test */
    function it_filters_incidents_based_on_status_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Incident::factory()->statusCancelled()->create();
        Incident::factory()->statusInProgress()->create();
        Incident::factory()->statusResolved()->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('Cancelled')
            ->assertSee('In Progress')
            ->assertSee('Resolved')
            ->set('searchCases.status.value', 'Can')
            ->assertSee('Cancelled')
            ->assertDontSee('In Progress')
            ->assertDontSee('Resolved')
            ->set('searchCases.status.value', 'In Prog')
            ->assertDontSee('Cancelled')
            ->assertSee('In Progress')
            ->assertDontSee('Resolved')
            ->set('searchCases.status.value', 'Resol')
            ->assertDontSee('Cancelled')
            ->assertDontSee('In Progress')
            ->assertSee('Resolved')
            ->set('searchCases.status.value', '')
            ->assertSee('Cancelled')
            ->assertSee('In Progress')
            ->assertSee('Resolved');
    }

    /** @test */
    function it_filters_incidents_based_on_priority_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        $incident_priority_one = Incident::factory([
            'id' => 99999996,
            'priority' => Priority::ONE
        ])->create();
        $incident_priority_two = Incident::factory([
            'id' => 99999997,
            'priority' => Priority::TWO
        ])->create();
        $incident_priority_three = Incident::factory([
            'id' => 99999998,
            'priority' => Priority::THREE
        ])->create();
        $incident_priority_four = Incident::factory([
            'id' => 99999999,
            'priority' => Priority::FOUR
        ])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee($incident_priority_one->id)
            ->assertSee($incident_priority_two->id)
            ->assertSee($incident_priority_three->id)
            ->assertSee($incident_priority_four->id)
            ->set('searchCases.priority.value', '1')
            ->assertSee($incident_priority_one->id)
            ->assertDontSee($incident_priority_two->id)
            ->assertDontSee($incident_priority_three->id)
            ->assertDontSee($incident_priority_four->id)
            ->set('searchCases.priority.value', '2')
            ->assertDontSee($incident_priority_one->id)
            ->assertSee($incident_priority_two->id)
            ->assertDontSee($incident_priority_three->id)
            ->assertDontSee($incident_priority_four->id)
            ->set('searchCases.priority.value', '3')
            ->assertDontSee($incident_priority_one->id)
            ->assertDontSee($incident_priority_two->id)
            ->assertSee($incident_priority_three->id)
            ->assertDontSee($incident_priority_four->id)
            ->set('searchCases.priority.value', '4')
            ->assertDontSee($incident_priority_one->id)
            ->assertDontSee($incident_priority_two->id)
            ->assertDontSee($incident_priority_three->id)
            ->assertSee($incident_priority_four->id)
            ->set('searchCases.priority.value', '')
            ->assertSee($incident_priority_one->id)
            ->assertSee($incident_priority_two->id)
            ->assertSee($incident_priority_three->id)
            ->assertSee($incident_priority_four->id);
    }

    /** @test */
    function it_paginates_25_incidents_per_page_by_default()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of incidents should be displayed on the first page
        Incident::factory(25, ['description' => 'Second page incident'])->create();
        Incident::factory(25, ['description' => 'First page incident'])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('First page incident')
            ->assertDontSee('Second page incident');
    }

    /** @test */
    function it_renders_incidents_based_on_pagination_index_input_field()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of incidents should be displayed on the first page
        Incident::factory(Table::DEFAULT_ITEMS_PER_PAGE)->create();
        Incident::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'Test incident'])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('Test incident')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('Test incident')
            ->set('paginationIndex', 1)
            ->assertSee('Test incident');
    }

    /**
     * @test
     * @dataProvider invalidPaginationIndexInput
     */
    function it_renders_first_pagination_page_if_pagination_index_input_is_invalid($invalidInput)
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of incidents should be displayed on the first page
        Incident::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'Second page incident'])->create();
        Incident::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'First page incident'])->create();

        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSee('First page incident')
            ->assertDontSee('Second page incident')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('First page incident')
            ->assertSee('Second page incident')
            ->set('paginationIndex', $invalidInput)
            ->assertSee('First page incident')
            ->assertDontSee('Second page incident');
    }

    static function invalidPaginationIndexInput(): array
    {
        return [
            [0],
            [(Table::DEFAULT_ITEMS_PER_PAGE * 2) + 1],
            ['zero'],
        ];
    }
}

