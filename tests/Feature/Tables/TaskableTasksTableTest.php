<?php

namespace Tables;

use App\Enums\Priority;
use App\Helpers\Table\Table;
use App\Livewire\Tables\TaskableTasksTable;
use App\Livewire\Tables\TasksTable;
use App\Models\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskableTasksTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_renders_column_headers_in_correct_order()
    {
        $taskable = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TaskableTasksTable::class, ['taskable' => $taskable])
            ->assertSeeInOrder(['Number', 'Description', 'Status']);
    }

    /** @test */
    function it_renders_taskable_tasks_data_in_correct_order()
    {
        $taskable = Request::factory()->taskSequenceAtOnce()->create();
        $tasks = $taskable->tasks;
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        foreach ($tasks as $task){
            Livewire::test(TaskableTasksTable::class, ['taskable' => $taskable])
                ->assertSeeInOrder([
                    $task->id,
                    $task->description,
                    $task->status->value,
                ]);
        }
    }

    /** @test */
    function it_renders_tasks_in_descending_order_by_default()
    {
        $taskable = Request::factory()->taskSequenceAtOnce()->create();
        $task_one = $taskable->tasks()->first();
        $task_two = $taskable->tasks()->skip(1)->first();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TaskableTasksTable::class, ['taskable' => $taskable])
            ->assertSeeInOrder([$task_one->description, $task_two->description]);
    }

    /** @test */
    function it_renders_tasks_in_descending_order_if_wire_click_on_number_header()
    {
        $taskable = Request::factory()->taskSequenceAtOnce()->create();
        $task_one = $taskable->tasks()->first();
        $task_two = $taskable->tasks()->skip(1)->first();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TaskableTasksTable::class, ['taskable' => $taskable])
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$task_two->description, $task_one->description]);
    }

    /** @test */
    function it_renders_tasks_in_ascending_order_if_wire_click_twice_on_number_header()
    {
        $taskable = Request::factory()->taskSequenceAtOnce()->create();
        $task_one = $taskable->tasks()->first();
        $task_two = $taskable->tasks()->skip(1)->first();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$task_one->caller->name, $task_two->caller->name]);
    }

    /** @test */
    function it_renders_tasks_in_ascending_order_if_wire_click_on_description_header()
    {
        $taskable = Request::factory()->taskSequenceAtOnce()->create();
        $task_one = $taskable->tasks()->first();
        $task_two = $taskable->tasks()->skip(1)->first();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'id')
            ->call('columnHeaderClicked', 'id')
            ->assertSeeInOrder([$task_two->description, $task_one->description]);
    }
}

