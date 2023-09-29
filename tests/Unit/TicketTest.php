<?php


use App\Models\Change;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Resolver;
use App\Models\Type;
use App\Models\Ticket;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Request;
use Tests\TestCase;

class TicketTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    use RefreshDatabase;



    function testIncidentTicketHasIncidentType()
    {
        $ticket = Incident::factory()->create();

        $this->assertEquals('incident', $ticket->type->name);
    }

    function testRequestTicketHasRequestType()
    {
        $ticket = Request::factory()->create();

        $this->assertEquals('request', $ticket->type->name);
    }

    function testChangeTicketHasChangeType()
    {
        $ticket = Change::factory()->create();

        $this->assertEquals('change', $ticket->type->name);
    }

    function testTicketsCanHaveAllPredefinedPriorities()
    {
        foreach (Ticket::PRIORITIES as $key => $value){
            $ticket = Incident::factory(['priority' => $value])->create();

            $this->assertEquals($value, $ticket->priority);
        }
    }

    function testSQLViolationThrowsWhenHigherPriorityThanPredefinedIsAssigned(){
        $this->expectException(QueryException::class);

        Ticket::factory(['priority' => count(Ticket::PRIORITIES) + 1])->create();
    }

    function testTicketHasCorrectDefaultPriority(){
        $ticket = new Ticket();

        $this->assertEquals(Ticket::DEFAULT_PRIORITY, $ticket->priority);
    }

    function testTicketsCanHaveAllPredefinedCategories()
    {
        foreach (Ticket::CATEGORIES as $key => $value){
            $ticket = Incident::factory(['category_id' => $value])->create();

            $this->assertEquals($value, $ticket->category_id);
        }
    }

    function testIncidentAndRequestTypesAcceptArbitraryTextMarkedAsDescription()
    {
        $incident = Incident::factory()->create();
        $request = Request::factory()->create();

        $incident->description = 'Incident description';
        $request->description = 'Request description';

        $this->assertEquals('Incident description', $incident->description);
        $this->assertEquals('Request description', $request->description);
    }

    function testResolversBelongToCorrectGroups(){
        $groupOne = Group::factory(['name' => 'Group One'])->create();
        $groupTwo = Group::factory(['name' => 'Group Two'])->create();

        $resolverOne = Resolver::factory()->create();
        $resolverTwo = Resolver::factory()->create();

        $resolverOne->groups()->attach($groupOne);
        $resolverTwo->groups()->attach($groupTwo);

        $this->assertEquals('Group One', $resolverOne->groups->all()[0]['name']);
        $this->assertEquals('Group Two', $resolverTwo->groups->all()[0]['name']);
    }

    function testResolversCanBelongToMultipleGroups(){
        $groupOne = Group::factory(['name' => 'Group One'])->create();
        $groupTwo = Group::factory(['name' => 'Group Two'])->create();

        $resolver = Resolver::factory()->create();
        $resolver->groups()->attach($groupOne);
        $resolver->groups()->attach($groupTwo);

        $this->assertEquals('Group One', $resolver->groups->all()[0]['name']);
        $this->assertEquals('Group Two', $resolver->groups->all()[1]['name']);
    }
}
