<?php

use App\Helpers\Slable;
use App\Models\Request;
use App\Models\RequestCategory;
use App\Models\RequestOnHoldReason;
use App\Models\RequestStatus;
use App\Models\Type;
use App\Models\User;
use App\Services\SlaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    function test_it_is_slable(){
        $request = Request::factory()->create();
        $this->assertInstanceOf(Slable::class, $request);
    }

    function test_it_has_many_slas(){
        $request = Request::factory()->create();
        SlaService::createSla($request);

        $this->assertCount(2, $request->slas);
    }

    function test_it_has_one_category()
    {
        $category = RequestCategory::findOrFail(RequestCategory::NETWORK);
        $request = Request::factory(['category_id' => $category])->create();

        $this->assertEquals($category->id, $request->category->id);
    }

    function test_it_has_one_resolver(){
        $resolver = User::factory(['name' => 'Average Joe'])->resolver()->create();
        $request = Request::factory(['resolver_id' => $resolver])->create();

        $this->assertEquals('Average Joe', $request->resolver->name);
    }
//
    public function test_it_belongs_to_status()
    {
        $status = RequestStatus::findOrFail(RequestStatus::OPEN);
        $request = Request::factory(['status_id' => $status])->create();

        $this->assertEquals($status->id, $request->status->id);
    }

    public function test_it_belongs_to_status_on_hold_reason()
    {
        $onHoldReason = RequestOnHoldReason::firstOrFail();
        $request = Request::factory(['on_hold_reason_id' => $onHoldReason])->create();

        $this->assertEquals($onHoldReason->id, $request->onHoldReason->id);
    }

    public function test_it_belongs_to_group()
    {
//        $group = Group::factory(['name' => 'LOCAL-6445-NEW-YORK'])->create();
//        $ticket = Ticket::factory(['group_id' => $group])->create();
//
//        $this->assertEquals('LOCAL-6445-NEW-YORK', $ticket->group->name);
    }

    public function test_it_belongs_to_item()
    {
//        $category = Category::firstOrFail();
//        $item = $category->items()->inRandomOrder()->first();
//        $ticket = Ticket::factory(['category_id' => $category, 'item_id' => $item])->create();
//
//        $this->assertEquals($item->name, $ticket->item->name);
    }

    function test_it_has_priority()
    {
//        $ticket = Ticket::factory(['priority' => 4])->create();
//
//        $this->assertEquals(4, $ticket->priority);
    }

    function test_it_has_description()
    {
//        $ticket = Ticket::factory(['description' => 'Ticket Description'])->create();
//
//        $this->assertEquals('Ticket Description', $ticket->description);
    }

    function test_it_has_correct_default_attributes(){
        $request = new Request();
        $this->assertEquals(Request::DEFAULT_STATUS, $request->status->id);
    }

    function test_it_gets_sla_assigned_based_on_priority(){
//        $ticket = Ticket::factory(['priority' => Ticket::DEFAULT_PRIORITY])->create();
//
//        $this->assertEquals(Ticket::PRIORITY_SLA[Ticket::DEFAULT_PRIORITY], $ticket->sla->minutes());
//
//        $ticket->priority = 3;
//        $ticket->save();
//        $ticket->refresh();
//
//        $this->assertEquals(Ticket::PRIORITY_SLA[3], $ticket->sla->minutes());
    }

    function test_sql_violation_thrown_when_higher_priority_than_predefined_is_assigned()
    {
//        $this->expectException(QueryException::class);
//
//        Ticket::factory(['priority' => count(Ticket::PRIORITIES) + 1])->create();
    }

    function test_it_has_correct_default_priority()
    {
//        $ticket = new Ticket();
//
//        $this->assertEquals(Ticket::DEFAULT_PRIORITY, $ticket->priority);
    }

    function test_it_has_correct_default_group(){
//        $ticket = new Ticket();
//
//        $this->assertEquals(Ticket::DEFAULT_GROUP, $ticket->group->id);
    }

    function test_it_has_resolved_at_timestamp_null_when_status_changes_from_resolved_to_different_status(){
//        $ticket = Ticket::factory()->create();
//        $ticket->status_id = Status::RESOLVED;
//        $ticket->save();
//
//        $ticket->status_id = Status::IN_PROGRESS;
//        $ticket->save();
//
//        $this->assertEquals(null, $ticket->resolved_at);
    }

    function test_it_cannot_have_status_resolved_and_resolved_at_timestamp_null(){
//        $ticket = Ticket::factory()->create();
//        $ticket->status_id = Status::RESOLVED;
//        $ticket->save();
//
//        $this->assertNotEquals(null, $ticket->resolved_at);
    }

    function test_it_is_not_archived_when_resolved_status_does_not_exceed_archival_period(){
//        $ticket = Ticket::factory()->create();
//        $ticket->status_id = Status::RESOLVED;
//        $ticket->save();
//
//        $date = Carbon::now()->addDays(Ticket::ARCHIVE_AFTER_DAYS - 1);
//        Carbon::setTestNow($date);
//
//        $this->assertFalse($ticket->isArchived());
    }

    function test_it_is_archived_when_resolved_status_exceeds_archival_period(){
//        $ticket = Ticket::factory()->create();
//        $ticket->status_id = Status::RESOLVED;
//        $ticket->save();
//
//        $date = Carbon::now()->addDays(Ticket::ARCHIVE_AFTER_DAYS);
//        Carbon::setTestNow($date);
//
//        $this->assertTrue($ticket->isArchived());
    }

    public function test_exception_thrown_if_item_does_not_match_category()
    {
//        // I'm detaching below models together, so they do not match
//        $category = Category::findOrFail(Category::NETWORK);
//        $item = Item::findOrFail(Item::APPLICATION_ERROR);
//        $category->items()->detach($item);
//
//        $this->withoutExceptionHandling();
//        $this->expectException(Exception::class);
//        $this->expectExceptionMessage('Item cannot be assigned to Ticket if it does not match Category');
//
//        Ticket::factory(['category_id' => $category, 'item_id' => $item])->create();
    }

    public function test_sla_resets_after_priority_is_changed()
    {
//        $ticket = Ticket::factory()->create();
//        $date = Carbon::now()->addMinutes(5);
//        Carbon::setTestNow($date);
//
//        // additional minute passes, as I'm running the test in real time
//        $this->assertEquals(Ticket::PRIORITY_SLA[$ticket->priority] - 6, $ticket->sla->minutesTillExpires());
//
//        $ticket->priority = 3;
//        $ticket->save();
//        $ticket->priority = 4;
//        $ticket->save();
//        $ticket->refresh();
//
//        // minute has to be subtracted, as when the test runs, time adjusts
//        $this->assertEquals(Ticket::PRIORITY_SLA[$ticket->priority] - 1, $ticket->sla->minutesTillExpires());
    }

    public function test_sla_has_24_hours_if_priority_is_4()
    {
//        $ticket = Ticket::factory(['priority' => 4])->create();
//
//        $this->assertEquals(24 * 60, $ticket->sla->minutes());
    }

    public function test_sla_has_12_hours_if_priority_is_3()
    {
//        $ticket = Ticket::factory(['priority' => 3])->create();
//
//        $this->assertEquals(12 * 60, $ticket->sla->minutes());
    }

    public function test_sla_has_2_hours_if_priority_is_2()
    {
//        $ticket = Ticket::factory(['priority' => 2])->create();
//
//        $this->assertEquals(2 * 60, $ticket->sla->minutes());
    }

    public function test_sla_has_30_minutes_if_priority_is_1()
    {
//        $ticket = Ticket::factory(['priority' => 1])->create();
//
//        $this->assertEquals(30, $ticket->sla->minutes());
    }

    public function test_sla_closes_itself_if_new_sla_is_created()
    {
//        $ticket = Ticket::factory()->create();
//        $sla = $ticket->sla;
//
//        $ticket->priority = 3;
//        $ticket->save();
//        $sla->refresh();
//
//        $this->assertNotNull($sla->closed_at);
    }
}
