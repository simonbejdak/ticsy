<?php

use App\Enums\Priority;
use App\Enums\TaskSequence;
use App\Interfaces\Slable;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Enums\OnHoldReason;
use App\Enums\Status;
use App\Models\Task;
use App\Models\User;
use App\Services\SlaService;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_is_slable(){
        $request = Request::factory()->create();
        $this->assertInstanceOf(Slable::class, $request);
    }

    /** @test */
    function it_has_many_slas(){
        $request = Request::factory()->create();
        SlaService::createSla($request);

        $this->assertCount(2, $request->slas);
    }

    /** @test */
    function it_has_one_category()
    {
        $category = RequestCategory::firstOrFail();
        $request = Request::factory(['category_id' => $category])->create();

        $this->assertEquals($category->id, $request->category->id);
    }

    /** @test */
    function it_has_one_resolver(){
        $resolver = User::factory(['name' => 'Average Joe'])->resolver()->create();
        $request = Request::factory(['resolver_id' => $resolver])->create();

        $this->assertEquals('Average Joe', $request->resolver->name);
    }

    /** @test */
    function it_has_many_tasks(){
        $request = Request::factory()->create();

        $this->assertInstanceOf(HasMany::class, $request->tasks());
    }

    /** @test */
    public function it_has_on_hold_reason_enum()
    {
        $request = Request::factory(['on_hold_reason' => OnHoldReason::CALLER_RESPONSE])->statusOnHold()->create();

        $this->assertEquals(OnHoldReason::CALLER_RESPONSE, $request->on_hold_reason);
    }

    /** @test */
    public function it_belongs_to_group()
    {
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $request = Request::factory(['group_id' => $group])->create();

        $this->assertEquals('TEST-GROUP', $request->group->name);
    }

    /** @test */
    public function it_belongs_to_item()
    {
        $item = RequestItem::firstOrFail();
        $request = Request::factory(['item_id' => $item])->make();

        $this->assertEquals($item->id, $request->item->id);
    }

    /** @test */
    function it_has_priority_enum()
    {
        $request = Request::factory(['priority' => Priority::THREE])->create();

        $this->assertEquals(Priority::THREE, $request->priority);
    }

    /** @test */
    function it_has_description()
    {
        $request = Request::factory(['description' => 'Request Description'])->create();

        $this->assertEquals('Request Description', $request->description);
    }

    /** @test */
    function it_has_correct_default_statuss(){
        $request = new Request();

        $this->assertEquals(Request::DEFAULT_STATUS, $request->status);
    }

    /** @test */
    function it_gets_sla_assigned_based_on_priority(){
        $request = Request::factory(['priority' => 4])->create();
        $this->assertEquals(Request::PRIORITY_TO_SLA_MINUTES[4], $request->sla->minutes());

        $request->priority = 3;
        $request->save();
        $request->refresh();

        $this->assertEquals(Request::PRIORITY_TO_SLA_MINUTES[3], $request->sla->minutes());
    }

    /** @test */
    function it_has_correct_default_priority()
    {
        $request = new Request();

        $this->assertEquals(Request::DEFAULT_PRIORITY, $request->priority);
    }

    /** @test */
    function it_has_correct_default_group(){
        $request = new Request();

        $this->assertEquals(Request::DEFAULT_GROUP, $request->group->id);
    }

    /** @test */
    function resolved_at_timestamp_is_not_null_when_status_is_closed(){
        $request = Request::factory()->statusResolved()->create();
        $this->assertNotNull($request->resolved_at);
    }

    /** @test */
    function resolved_at_timestamp_is_null_when_status_is_not_closed(){
        $request = Request::factory()->create();
        $this->assertNull($request->resolved_at);
    }

    /** @test */
    function resolved_at_timestamp_is_null_when_status_changes_from_closed_to_different_status(){
        $request = Request::factory()->statusResolved()->create();
        $this->assertNotNull($request->resolved_at);

        $request->status = Status::IN_PROGRESS;
        $request->save();

        $this->assertNull($request->resolved_at);
    }

    /** @test */
    function resolved_at_timestamp_is_not_null_when_status_changes_to_status_closed(){
        $request = Request::factory()->create();
        $this->assertNull($request->resolved_at);

        $request->status = Status::RESOLVED;
        $request->save();

        $this->assertNotNull($request->resolved_at);
    }

    /** @test */
    function it_is_archived_when_status_closed_exceeds_archival_period(){
        $request = Request::factory()->statusResolved()->create();
        $date = Carbon::now()->addDays(Request::ARCHIVE_AFTER_DAYS);
        Carbon::setTestNow($date);

        $this->assertTrue($request->isArchived());
    }

    /** @test */
    function it_is_not_archived_when_closed_status_does_not_exceed_archival_period(){
        $request = Request::factory()->statusResolved()->create();
        $date = Carbon::now()->addDays(Request::ARCHIVE_AFTER_DAYS - 1);
        Carbon::setTestNow($date);

        $this->assertFalse($request->isArchived());
    }

    /** @test */
    public function exception_thrown_if_item_does_not_match_category()
    {
        // I have to detach below models just to be sure, that in no event they are paired
        $category = RequestCategory::firstOrFail();
        $item = RequestItem::firstOrFail();
        $category->items()->detach($item);

        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Item cannot be assigned to Request if it does not match Category');

        Request::factory(['category_id' => $category, 'item_id' => $item])->create();
    }

    /** @test */
    public function sla_resets_after_priority_is_changed()
    {
        $request = Request::factory()->create();
        $date = Carbon::now()->addMinutes(5);
        Carbon::setTestNow($date);

        // additional minute passes, as the test runs in real time
        $this->assertEquals(Request::PRIORITY_TO_SLA_MINUTES[$request->priority->value] - 6, $request->sla->minutesTillExpires());

        $request->priority = Priority::THREE;
        $request->save();
        $request->priority = Priority::FOUR;
        $request->save();
        $request->refresh();

        // minute has to be subtracted, as when the test runs, time adjusts
        $this->assertEquals(Request::PRIORITY_TO_SLA_MINUTES[$request->priority->value] - 1, $request->sla->minutesTillExpires());
    }

    /**
     * @test
     * @dataProvider priorityToSlaMinutes
     */
    public function sla_minutes_match_priorities_according_to_data_provider($priority, $slaMinutes)
    {
        $request = Request::factory(['priority' => $priority])->create();

        $this->assertEquals($slaMinutes, $request->sla->minutes());
    }

    /** @test */
    public function sla_closes_itself_if_new_sla_is_created()
    {
        $request = Request::factory()->create();
        $sla = $request->sla;

        $this->assertNull($sla->closed_at);

        $request->priority = 3;
        $request->save();
        $sla->refresh();

        $this->assertNotNull($sla->closed_at);
    }

    /** @test */
    function if_task_sequence_is_gradual_first_task_is_started(){
        $request = Request::factory()->taskSequenceGradient()->create();
        $firstTask = $request->tasks->first();

        $this->assertNotNull($firstTask->started_at);
    }

    /** @test */
    function if_task_sequence_is_gradual_second_task_is_not_started(){
        $request = Request::factory()->taskSequenceGradient()->create();
        $secondTask = $request->tasks->skip(1)->first();

        $this->assertNull($secondTask->started_at);
    }

    /** @test */
    function if_task_sequence_is_gradual_second_task_starts_after_first_task_is_resolved(){
        $request = Request::factory()->taskSequenceGradient()->create();
        $firstTask = $request->tasks->first();
        $secondTask = $request->tasks->skip(1)->first();

        $this->assertNull($secondTask->started_at);

        TaskService::resolveTask($firstTask);
        $secondTask->refresh();

        $this->assertNotNull($secondTask->started_at);
    }

    /** @test */
    function if_task_sequence_is_at_once_second_task_starts_after_request_is_created(){
        $request = Request::factory()->taskSequenceAtOnce()->create();
        $firstTask = $request->tasks->first();
        $secondTask = $request->tasks->skip(1)->first();

        $this->assertNotNull($firstTask->started_at);
        $this->assertNotNull($secondTask->started_at);
    }

    /** @test */
    function if_task_sequence_is_at_once_second_task_does_not_start_after_first_task_is_resolved(){
        $request = Request::factory()->taskSequenceAtOnce()->create();
        $firstTask = $request->tasks->first();
        $secondTask = $request->tasks->skip(1)->first();
        $secondTaskStartTime = $secondTask->started_at;

        TaskService::resolveTask($firstTask);
        $secondTaskStartTimeAfterFirstTaskResolved = $secondTask->refresh()->started_at;

        $this->assertEquals($secondTaskStartTime, $secondTaskStartTimeAfterFirstTaskResolved);
    }

    /** @test */
    function it_becomes_resolved_when_all_tasks_become_resolved(){
        $request = Request::factory()->create();
        $tasks = $request->tasks;

        $this->assertFalse($request->isStatus(Status::RESOLVED));

        foreach ($tasks as $task){
            TaskService::resolveTask($task);
        }
        $request->refresh();

        $this->assertTrue($request->isStatus(Status::RESOLVED));
    }

    /** @test */
    function it_becomes_cancelled_when_one_task_is_cancelled(){
        $request = Request::factory()->create();
        $task = $request->tasks->first();

        $this->assertFalse($request->isStatus(Status::CANCELLED));

        TaskService::cancelTask($task);
        $request->refresh();

        $this->assertTrue($request->isStatus(Status::CANCELLED));
    }

    /** @test */
    function it_creates_task_with_same_description_as_request_if_category_item_pair_has_no_task_plan(){
        $request = Request::factory(['description' => 'Please do the needful'])->withoutTaskPlan()->create();

        $this->assertEquals('Please do the needful', $request->tasks->first()->description);
    }

    static function priorityToSlaMinutes(){
        return [
            [1, 30],
            [2, 120],
            [3, 720],
            [4, 1440],
        ];
    }
}
