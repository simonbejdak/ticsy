<?php

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Request;
use App\Models\Request\RequestCategory;
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
    function it_morphs_to_taskable_for_request(){
        $request = Request::factory(['description' => 'Request Description'])->create();
        $task = Task::factory()->create()->taskable()->associate($request);

        $this->assertEquals('Request Description', $task->taskable->description);
    }

    /** @test */
    function it_returns_request_category_name_through_categoryName_method(){
        $category = RequestCategory::firstOrFail();
        $request = Request::factory(['category_id' => $category])->create();
        $task = Task::factory()->create()->taskable()->associate($request);

        $this->assertEquals($task->categoryName(), $request->category->name);
    }

    /** @test */
    function it_has_resolved_at_timestamp_attribute(){
        $task = Task::factory(['resolved_at' => Carbon::now()])->create();

        $this->assertNotNull($task->resolved_at);
    }

    /** @test */
    function its_priority_is_updated_based_on_request(){
        $request = Request::factory(['priority' => Priority::FOUR])->withoutTaskPlan()->create();
        $task = $request->tasks()->first();

        $this->assertEquals(Priority::FOUR, $task->priority);

        $request->priority = Priority::THREE;
        $request->save();
        $task->refresh();

        $this->assertEquals(Priority::THREE, $task->priority);
    }

    /** @test */
    function it_is_not_archived_when_it_is_created(){
        $task = Task::factory()->create();

        $this->assertFalse($task->isArchived());
    }

    /** @test */
    function as_soon_as_status_is_set_to_resolved_task_is_archived(){
        $task = Request::factory()->create()->tasks()->first();

        $task->status = Status::RESOLVED;
        $task->save();
        $task->refresh();

        $this->assertTrue($task->isArchived());
    }

    /** @test */
    function as_soon_as_status_is_set_to_cancelled_task_is_archived(){
        $task = Task::factory()->create();

        $task->status = Status::CANCELLED;
        $task->save();
        $task->refresh();

        $this->assertTrue($task->isArchived());
    }

    /** @test */
    function hasTaskable_method_returns_false_if_taskable_is_not_assigned(){
        $task = Task::factory()->create();

        $this->assertFalse($task->hasTaskable());
    }

    /**
     * @test
     * @dataProvider slaClosingStatuses
     */
    function sla_is_closed_when_status_changes_to_sla_closing_statuses($status){
        $task = Task::factory()->create();

        $this->assertFalse($task->sla->isClosed());

        $task->status = $status;
        $task->save();
        $task->refresh();

        $this->assertTrue($task->sla->isClosed());
    }

    static function slaClosingStatuses(){
        return [
            [Status::ON_HOLD],
            [Status::RESOLVED],
            [Status::CANCELLED],
        ];
    }
}
