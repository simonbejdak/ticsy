<?php

namespace Tables;

use App\Enums\Priority;
use App\Livewire\Table;
use App\Livewire\Tables\TasksTable;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TasksTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_renders_column_headers_in_correct_order()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.tasks'));

        $response->assertSeeInOrder(['Number', 'Description', 'Caller', 'Resolver', 'Status', 'Priority']);
    }

    /** @test */
    function it_renders_tasks_data_in_correct_order()
    {
        $tasks = Task::factory(3)->started()->withResolver()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.tasks'));
        foreach ($tasks as $task) {
            $response->assertSeeInOrder([
                $task->id,
                $task->description,
                $task->caller->name,
                $task->resolver->name,
                $task->status->value,
                $task->priority->value,
            ]);
        }
    }

    /** @test */
    function it_renders_tasks_in_descending_order_by_default()
    {
        $task_one = Task::factory()->started()->started()->create();
        $task_two = Task::factory()->started()->started()->create();
        $task_three = Task::factory()->started()->started()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.tasks'));

        $response->assertSeeInOrder([$task_three->caller->name, $task_two->caller->name, $task_one->caller->name]);
    }

    /** @test */
    function it_renders_tasks_in_ascending_order_if_wire_click_on_number_header()
    {
        $task_one = Task::factory()->started()->create();
        $task_two = Task::factory()->started()->create();
        $task_three = Task::factory()->started()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$task_one->caller->name, $task_two->caller->name, $task_three->caller->name]);
    }

    /** @test */
    function it_renders_tasks_in_descending_order_if_wire_click_twice_on_number_header()
    {
        $task_one = Task::factory()->started()->create();
        $task_two = Task::factory()->started()->create();
        $task_three = Task::factory()->started()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$task_three->caller->name, $task_two->caller->name, $task_one->caller->name]);
    }

    /** @test */
    function it_renders_tasks_in_ascending_order_if_wire_click_on_priority_header()
    {
        $task_one = Task::factory()->started()->create();
        $task_two = Task::factory()->started()->create();
        $task_three = Task::factory()->started()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$task_three->caller->name, $task_two->caller->name, $task_one->caller->name]);
    }

    /** @test */
    function it_renders_tasks_in_ascending_order_based_on_priority_if_wire_click_on_priority_header()
    {
        $resolver = User::factory()->resolver()->create();
        $task_one = Task::factory(['priority' => Priority::ONE])->create();
        $task_two = Task::factory(['priority' => Priority::TWO])->create();
        $task_three = Task::factory(['priority' => Priority::THREE])->create();
        $task_four = Task::factory(['priority' => Priority::FOUR])->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'priority.value')
            ->assertSeeInOrder([Priority::ONE->value, Priority::TWO->value, Priority::THREE->value, Priority::FOUR->value]);
    }

    /** @test */
    function it_filters_tasks_based_on_id_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Task::factory(['id' => 1234])->started()->create();
        Task::factory(['id' => 5678])->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
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
    function it_filters_tasks_based_on_description_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Task::factory([
            'description' => 'My computer is not working'
        ])->started()->create();
        Task::factory([
            'description' => 'Server is down'
        ])->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
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
    function it_filters_tasks_based_on_caller_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->withCaller(User::factory(['name' => 'Eugen Slavic'])->create())->started()->create();
        $task = Task::factory()->withCaller(User::factory(['name' => 'Anastasia Yosling'])->create())->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
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
    function it_filters_tasks_based_on_resolver_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Task::factory([
            'resolver_id' => User::factory(['name' => 'Eugen Slavic'])->resolver()->create()
        ])->started()->create();
        Task::factory([
            'resolver_id' => User::factory(['name' => 'Anastasia Yosling'])->resolver()->create()
        ])->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
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
    function it_filters_tasks_based_on_status_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        Task::factory()->started()->statusCancelled()->create();
        Task::factory()->started()->statusInProgress()->create();
        Task::factory()->started()->statusResolved()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
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
    function it_filters_tasks_based_on_priority_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        $task_priority_one = Task::factory([
            'id' => 99999996,
            'priority' => Priority::ONE
        ])->started()->create();
        $task_priority_two = Task::factory([
            'id' => 99999997,
            'priority' => Priority::TWO
        ])->started()->create();
        $task_priority_three = Task::factory([
            'id' => 99999998,
            'priority' => Priority::THREE
        ])->started()->create();
        $task_priority_four = Task::factory([
            'id' => 99999999,
            'priority' => Priority::FOUR
        ])->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->assertSee($task_priority_one->id)
            ->assertSee($task_priority_two->id)
            ->assertSee($task_priority_three->id)
            ->assertSee($task_priority_four->id)
            ->set('searchCases.priority.value', '1')
            ->assertSee($task_priority_one->id)
            ->assertDontSee($task_priority_two->id)
            ->assertDontSee($task_priority_three->id)
            ->assertDontSee($task_priority_four->id)
            ->set('searchCases.priority.value', '2')
            ->assertDontSee($task_priority_one->id)
            ->assertSee($task_priority_two->id)
            ->assertDontSee($task_priority_three->id)
            ->assertDontSee($task_priority_four->id)
            ->set('searchCases.priority.value', '3')
            ->assertDontSee($task_priority_one->id)
            ->assertDontSee($task_priority_two->id)
            ->assertSee($task_priority_three->id)
            ->assertDontSee($task_priority_four->id)
            ->set('searchCases.priority.value', '4')
            ->assertDontSee($task_priority_one->id)
            ->assertDontSee($task_priority_two->id)
            ->assertDontSee($task_priority_three->id)
            ->assertSee($task_priority_four->id)
            ->set('searchCases.priority.value', '')
            ->assertSee($task_priority_one->id)
            ->assertSee($task_priority_two->id)
            ->assertSee($task_priority_three->id)
            ->assertSee($task_priority_four->id);
    }

    /** @test */
    function it_paginates_25_tasks_per_page_by_default()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of tasks should be displayed on the first page
        Task::factory(25, ['description' => 'Second page task'])->started()->create();
        Task::factory(25, ['description' => 'First page task'])->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->assertSee('First page task')
            ->assertDontSee('Second page task');
    }

    /** @test */
    function it_renders_tasks_based_on_pagination_index_input_field()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of tasks should be displayed on the first page
        Task::factory(Table::DEFAULT_ITEMS_PER_PAGE)->started()->create();
        Task::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'Test task'])->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->assertSee('Test task')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('Test task')
            ->set('paginationIndex', 1)
            ->assertSee('Test task');
    }

    /**
     * @test
     * @dataProvider invalidPaginationIndexInput
     */
    function it_renders_first_pagination_page_if_pagination_index_input_is_invalid($invalidInput)
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of tasks should be displayed on the first page
        Task::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'Second page task'])->started()->create();
        Task::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['description' => 'First page task'])->started()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->assertSee('First page task')
            ->assertDontSee('Second page task')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('First page task')
            ->assertSee('Second page task')
            ->set('paginationIndex', $invalidInput)
            ->assertSee('First page task')
            ->assertDontSee('Second page task');
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

