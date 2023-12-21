<?php

use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_number_attribute(){
        $task = Task::factory()->create();
        $this->assertNotNull($task->number);
    }

    /** @test */
    function it_has_description_attribute(){
        $task = Task::factory()->create();
        $this->assertNotNull($task->description);
    }

    /** @test */
    function it_belongs_to_request(){
        $request = Request::factory(['description' => 'Request Description'])->create();
        $task = Task::factory(['request_id' => $request])->create();

        $this->assertEquals('Request Description', $task->request->description);
    }

    /** @test */
    function it_has_one_category_through_request(){
        $category = RequestCategory::firstOrFail();
        $request = Request::factory(['category_id' => $category])->create();
        $task = Task::factory(['request_id' => $request])->create();

        $this->assertTrue($task->category->is($request->category));
    }

    /** @test */
    function it_has_resolved_at_timestamp_attribute(){
        $task = Task::factory(['resolved_at' => Carbon::now()])->create();

        $this->assertNotNull($task->resolved_at);
    }

    /** @test */
    function priority_is_set_based_on_request_priority(){
        $request = Request::factory(['priority' => 3])->create();
        $task = Task::factory(['request_id' => $request])->create();

        $this->assertEquals(3, $task->priority);
    }

    /** @test */
    function it_is_not_archived_when_it_is_created(){
        $task = Task::factory()->create();

        $this->assertFalse($task->isArchived());
    }

    /** @test */
    function as_soon_as_status_is_set_to_resolved_task_is_archived(){
        $task = Task::factory()->create();

        $task->status_id = Status::RESOLVED;
        $task->save();
        $task->refresh();

        $this->assertTrue($task->isArchived());
    }

    /** @test */
    function as_soon_as_status_is_set_to_cancelled_task_is_archived(){
        $task = Task::factory()->create();

        $task->status_id = Status::CANCELLED;
        $task->save();
        $task->refresh();

        $this->assertTrue($task->isArchived());
    }

}
