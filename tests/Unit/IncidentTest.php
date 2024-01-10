<?php


namespace Tests\Unit;

use App\Interfaces\Slable;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Enums\OnHoldReason;
use App\Enums\Status;
use App\Models\User;
use App\Services\SlaService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class IncidentTest extends TestCase
{
    use RefreshDatabase;

    function test_it_is_slable(){
        $incident = Incident::factory()->create();
        $this->assertTrue($incident instanceof Slable);
    }

    function test_it_has_many_slas(){
        $incident = Incident::factory()->create();
        SlaService::createSla($incident);

        $this->assertCount(2, $incident->slas);
    }

    function test_it_has_one_category()
    {
        $category = IncidentCategory::findOrFail(IncidentCategory::NETWORK);
        $incident = Incident::factory(['category_id' => $category])->create();

        $this->assertEquals('Network', $incident->category->name);
    }

    function test_it_has_one_resolver(){
        $resolver = User::factory(['name' => 'John Doe'])->create()->assignRole('resolver');
        $incident = Incident::factory(['resolver_id' => $resolver])->create();

        $this->assertEquals('John Doe', $incident->resolver->name);
    }

    public function test_it_belongs_to_status_on_hold_reason()
    {
        $incident = Incident::factory(['on_hold_reason' => OnHoldReason::CALLER_RESPONSE])->statusOnHold()->create();

        $this->assertEquals('Caller Response', $incident->onHoldReason->name);
    }

    public function test_it_belongs_to_group()
    {
        $group = Group::findOrFail(Group::LOCAL_6445_NEW_YORK);
        $incident = Incident::factory(['group_id' => $group])->create();

        $this->assertEquals('LOCAL-6445-NEW-YORK', $incident->group->name);
    }

    public function test_it_belongs_to_item()
    {
        $category = IncidentCategory::firstOrFail();
        $item = $category->items()->inRandomOrder()->first();
        $incident = Incident::factory(['category_id' => $category, 'item_id' => $item])->create();

        $this->assertEquals($item->name, $incident->item->name);
    }

    function test_it_has_priority()
    {
        $incident = Incident::factory(['priority' => 4])->create();

        $this->assertEquals(4, $incident->priority);
    }

    function test_it_has_description()
    {
        $incident = Incident::factory(['description' => 'Incident Description'])->create();

        $this->assertEquals('Incident Description', $incident->description);
    }

    function test_it_gets_sla_assigned_based_on_priority(){
        $incident = Incident::factory(['priority' => Incident::DEFAULT_PRIORITY])->create();

        $this->assertEquals(Incident::PRIORITY_TO_SLA_MINUTES[Incident::DEFAULT_PRIORITY], $incident->sla->minutes());

        $incident->priority = 3;
        $incident->save();
        $incident->refresh();

        $this->assertEquals(Incident::PRIORITY_TO_SLA_MINUTES[3], $incident->sla->minutes());
    }

    function test_sql_violation_thrown_when_higher_priority_than_predefined_is_assigned()
    {
        $this->expectException(QueryException::class);

        Incident::factory(['priority' => count(Incident::PRIORITIES) + 1])->create();
    }

    function test_it_has_correct_default_priority()
    {
        $incident = new Incident();

        $this->assertEquals(Incident::DEFAULT_PRIORITY, $incident->priority);
    }

    function test_it_has_correct_default_group(){
        $incident = new Incident();

        $this->assertEquals(Incident::DEFAULT_GROUP, $incident->group->id);
    }

    function test_it_has_resolved_at_timestamp_null_when_status_changes_from_resolved_to_different_status(){
        $incident = Incident::factory()->create();
        $incident->status = Status::RESOLVED;
        $incident->save();

        $incident->status = Status::IN_PROGRESS;
        $incident->save();

        $this->assertEquals(null, $incident->resolved_at);
    }

    function test_it_cannot_have_status_resolved_and_resolved_at_timestamp_null(){
        $incident = Incident::factory()->create();
        $incident->status = Status::RESOLVED;
        $incident->save();

        $this->assertNotEquals(null, $incident->resolved_at);
    }

    function test_it_is_not_archived_when_resolved_status_does_not_exceed_archival_period(){
        $incident = Incident::factory()->create();
        $incident->status = Status::RESOLVED;
        $incident->save();

        $date = Carbon::now()->addDays(Incident::ARCHIVE_AFTER_DAYS - 1);
        Carbon::setTestNow($date);

        $this->assertFalse($incident->isArchived());
    }

    function test_it_is_archived_when_resolved_status_exceeds_archival_period(){
        $incident = Incident::factory()->create();
        $incident->status = Status::RESOLVED;
        $incident->save();

        $date = Carbon::now()->addDays(Incident::ARCHIVE_AFTER_DAYS);
        Carbon::setTestNow($date);

        $this->assertTrue($incident->isArchived());
    }

    public function test_exception_thrown_if_item_does_not_match_category()
    {
        // I'm detaching below models together, so they do not match
        $category = IncidentCategory::findOrFail(IncidentCategory::NETWORK);
        $item = IncidentItem::findOrFail(IncidentItem::APPLICATION_ERROR);
        $category->items()->detach($item);

        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Item cannot be assigned to Incident if it does not match Category');

        Incident::factory(['category_id' => $category, 'item_id' => $item])->create();
    }

    public function test_sla_resets_after_priority_is_changed()
    {
        $incident = Incident::factory()->create();
        $date = Carbon::now()->addMinutes(5);
        Carbon::setTestNow($date);

        // additional minute passes, as I'm running the test in real time
        $this->assertEquals(Incident::PRIORITY_TO_SLA_MINUTES[$incident->priority] - 6, $incident->sla->minutesTillExpires());

        $incident->priority = 3;
        $incident->save();
        $incident->priority = 4;
        $incident->save();
        $incident->refresh();

        // minute has to be subtracted, as when the test runs, time adjusts
        $this->assertEquals(Incident::PRIORITY_TO_SLA_MINUTES[$incident->priority] - 1, $incident->sla->minutesTillExpires());
    }

    /**
     * @test
     * @dataProvider priorityToSlaMinutes
     */
    public function sla_minutes_match_priorities_according_to_data_provider($priority, $slaMinutes)
    {
        $incident = Incident::factory(['priority' => $priority])->create();

        $this->assertEquals($slaMinutes, $incident->sla->minutes());
    }

    public function test_sla_closes_itself_if_new_sla_is_created()
    {
        $incident = Incident::factory()->create();
        $sla = $incident->sla;

        $incident->priority = 3;
        $incident->save();
        $sla->refresh();

        $this->assertNotNull($sla->closed_at);
    }

    static function priorityToSlaMinutes(){
        return [
            [1, 15],
            [2, 60],
            [3, 360],
            [4, 720],
        ];
    }
}
