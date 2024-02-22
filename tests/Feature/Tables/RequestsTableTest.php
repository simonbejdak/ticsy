<?php

namespace Tables;

use App\Enums\Priority;
use App\Livewire\Table;
use App\Livewire\Tables\RequestsTable;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RequestsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_renders_column_headers_in_correct_order()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.requests'));

        $response->assertSeeInOrder(['Number', 'Description', 'Caller', 'Resolver', 'Status', 'Priority']);
    }

    /** @test */
    function it_renders_requests_data_in_correct_order()
    {
        $requests = Request::factory(3)->withResolver()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.requests'));
        foreach ($requests as $request) {
            $response->assertSeeInOrder([
                $request->id,
                $request->description,
                $request->caller->name,
                $request->resolver->name,
                $request->status->value,
                $request->priority->value,
            ]);
        }
    }

    /** @test */
    function it_renders_requests_in_descending_order_by_default()
    {
        $request_one = Request::factory()->create();
        $request_two = Request::factory()->create();
        $request_three = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.requests'));

        $response->assertSeeInOrder([$request_three->caller->name, $request_two->caller->name, $request_one->caller->name]);
    }

    /** @test */
    function it_renders_requests_in_ascending_order_if_wire_click_on_number_header()
    {
        $request_one = Request::factory()->create();
        $request_two = Request::factory()->create();
        $request_three = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$request_one->caller->name, $request_two->caller->name, $request_three->caller->name]);
    }

    /** @test */
    function it_renders_requests_in_descending_order_if_wire_click_twice_on_number_header()
    {
        $request_one = Request::factory()->create();
        $request_two = Request::factory()->create();
        $request_three = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$request_three->caller->name, $request_two->caller->name, $request_one->caller->name]);
    }

    /** @test */
    function it_renders_requests_in_ascending_order_if_wire_click_on_priority_header()
    {
        $request_one = Request::factory()->create();
        $request_two = Request::factory()->create();
        $request_three = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$request_three->caller->name, $request_two->caller->name, $request_one->caller->name]);
    }

    /** @test */
    function it_renders_requests_in_ascending_order_based_on_priority_if_wire_click_on_priority_header()
    {
        $resolver = User::factory()->resolver()->create();
        $request_one = Request::factory(['priority' => Priority::ONE])->create();
        $request_two = Request::factory(['priority' => Priority::TWO])->create();
        $request_three = Request::factory(['priority' => Priority::THREE])->create();
        $request_four = Request::factory(['priority' => Priority::FOUR])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->call('columnHeaderClicked', 'priority.value')
            ->assertSeeInOrder([Priority::ONE->value, Priority::TWO->value, Priority::THREE->value, Priority::FOUR->value]);
    }

    /** @test */
    function it_filters_requests_based_on_id_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Request::factory(['id' => 1234])->create();
        Request::factory(['id' => 5678])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
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
    function it_filters_requests_based_on_description_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Request::factory([
            'description' => 'My computer is not working'
        ])->create();
        Request::factory([
            'description' => 'Server is down'
        ])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
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
    function it_filters_requests_based_on_caller_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Request::factory([
            'caller_id' => User::factory(['name' => 'Eugen Slavic'])->create()
        ])->create();
        Request::factory([
            'caller_id' => User::factory(['name' => 'Anastasia Yosling'])->create()
        ])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
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
    function it_filters_requests_based_on_resolver_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Request::factory([
            'resolver_id' => User::factory(['name' => 'Eugen Slavic'])->resolver()->create()
        ])->create();
        Request::factory([
            'resolver_id' => User::factory(['name' => 'Anastasia Yosling'])->resolver()->create()
        ])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
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
    function it_filters_requests_based_on_status_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Request::factory()->statusCancelled()->create();
        Request::factory()->statusInProgress()->create();
        Request::factory()->statusResolved()->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
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
    function it_filters_requests_based_on_priority_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        $request_priority_one = Request::factory([
            'id' => 99999996,
            'priority' => Priority::ONE
        ])->create();
        $request_priority_two = Request::factory([
            'id' => 99999997,
            'priority' => Priority::TWO
        ])->create();
        $request_priority_three = Request::factory([
            'id' => 99999998,
            'priority' => Priority::THREE
        ])->create();
        $request_priority_four = Request::factory([
            'id' => 99999999,
            'priority' => Priority::FOUR
        ])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->assertSee($request_priority_one->id)
            ->assertSee($request_priority_two->id)
            ->assertSee($request_priority_three->id)
            ->assertSee($request_priority_four->id)
            ->set('searchCases.priority.value', '1')
            ->assertSee($request_priority_one->id)
            ->assertDontSee($request_priority_two->id)
            ->assertDontSee($request_priority_three->id)
            ->assertDontSee($request_priority_four->id)
            ->set('searchCases.priority.value', '2')
            ->assertDontSee($request_priority_one->id)
            ->assertSee($request_priority_two->id)
            ->assertDontSee($request_priority_three->id)
            ->assertDontSee($request_priority_four->id)
            ->set('searchCases.priority.value', '3')
            ->assertDontSee($request_priority_one->id)
            ->assertDontSee($request_priority_two->id)
            ->assertSee($request_priority_three->id)
            ->assertDontSee($request_priority_four->id)
            ->set('searchCases.priority.value', '4')
            ->assertDontSee($request_priority_one->id)
            ->assertDontSee($request_priority_two->id)
            ->assertDontSee($request_priority_three->id)
            ->assertSee($request_priority_four->id)
            ->set('searchCases.priority.value', '')
            ->assertSee($request_priority_one->id)
            ->assertSee($request_priority_two->id)
            ->assertSee($request_priority_three->id)
            ->assertSee($request_priority_four->id);
    }

    /** @test */
    function it_paginates_25_requests_per_page_by_default()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of requests should be displayed on the first page
        Request::factory(25, ['description' => 'Second page request'])->create();
        Request::factory(25, ['description' => 'First page request'])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->assertSee('First page request')
            ->assertDontSee('Second page request');
    }

    /** @test */
    function it_renders_requests_based_on_pagination_index_input_field()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of requests should be displayed on the first page
        Request::factory(Table::DEFAULT_ITEMS_PER_PAGE)->create();
        Request::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'Test request'])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->assertSee('Test request')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('Test request')
            ->set('paginationIndex', 1)
            ->assertSee('Test request');
    }

    /**
     * @test
     * @dataProvider invalidPaginationIndexInput
     */
    function it_renders_first_pagination_page_if_pagination_index_input_is_invalid($invalidInput)
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of requests should be displayed on the first page
        Request::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'Second page request'])->create();
        Request::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'First page request'])->create();

        Livewire::actingAs($resolver)
            ->test(RequestsTable::class)
            ->assertSee('First page request')
            ->assertDontSee('Second page request')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('First page request')
            ->assertSee('Second page request')
            ->set('paginationIndex', $invalidInput)
            ->assertSee('First page request')
            ->assertDontSee('Second page request');
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

