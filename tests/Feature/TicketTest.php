<?php


namespace Tests\Feature;

use App\Models\Change;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Request;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $ticket = Ticket::factory(['type_id' => Ticket::TYPES['incident']])
            ->create();

        $this->assertEquals('Incident', $ticket->type->name);
    }

    function testRequestTicketHasRequestType()
    {
        $ticket = Ticket::factory(['type_id' => Ticket::TYPES['request']])
            ->create();

        $this->assertEquals('Request', $ticket->type->name);
    }

    function testChangeTicketHasChangeType()
    {
        $ticket = Ticket::factory(['type_id' => Ticket::TYPES['change']])
            ->create();

        $this->assertEquals('Change', $ticket->type->name);
    }

    function testTicketsCanHaveAllPredefinedPriorities()
    {
        foreach (Ticket::PRIORITIES as $PRIORITY) {
            $ticket = Ticket::factory(['priority' => $PRIORITY])->create();

            $this->assertEquals($PRIORITY, $ticket->priority);
        }
    }

    function testSQLViolationThrowsWhenHigherPriorityThanPredefinedIsAssigned()
    {
        $this->expectException(QueryException::class);

        Ticket::factory(['priority' => count(Ticket::PRIORITIES) + 1])->create();
    }

    function testTicketHasCorrectDefaultPriority()
    {
        $ticket = new Ticket();

        $this->assertEquals(Ticket::DEFAULT_PRIORITY, $ticket->priority);
    }

    function testTicketsCanHaveAllPredefinedCategories()
    {
        foreach (Ticket::CATEGORIES as $key => $value) {
            $ticket = Ticket::factory(['category_id' => $value])->create();

            $this->assertEquals($value, $ticket->category_id);
        }
    }

    function testIncidentAndRequestTypesAcceptArbitraryTextMarkedAsDescription()
    {
        $incident = Ticket::factory([
                'type_id' => Ticket::TYPES['incident'],
                'description' => 'Incident description',
            ])->create();

        $request = Ticket::factory([
            'type_id' => Ticket::TYPES['request'],
            'description' => 'Request description',
        ])->create();

        $this->assertEquals('Incident description', $incident->description);
        $this->assertEquals('Request description', $request->description);
    }

    function testResolversCanBelongToGroups()
    {
        $groupOne = Group::factory(['name' => 'Group One'])->create();
        $groupTwo = Group::factory(['name' => 'Group Two'])->create();

        $resolverOne = Resolver::factory()->create();
        $resolverTwo = Resolver::factory()->create();

        $resolverOne->groups()->attach($groupOne);
        $resolverTwo->groups()->attach($groupTwo);

        $this->assertEquals('Group One', $resolverOne->groups->all()[0]['name']);
        $this->assertEquals('Group Two', $resolverTwo->groups->all()[0]['name']);
    }

    function testResolverCanBelongToMultipleGroups()
    {
        $groupOne = Group::factory(['name' => 'Group One'])->create();
        $groupTwo = Group::factory(['name' => 'Group Two'])->create();

        $resolver = Resolver::factory()->create();
        $resolver->groups()->attach($groupOne);
        $resolver->groups()->attach($groupTwo);

        $this->assertEquals('Group One', $resolver->groups->all()[0]['name']);
        $this->assertEquals('Group Two', $resolver->groups->all()[1]['name']);
    }

    function testOnlyOneResolverCanBeAssignedToTicket()
    {
        $ticket = Ticket::factory()->create();
        $resolverOne = Resolver::factory()->create();
        $resolverTwo = Resolver::factory()->create();

        $ticket->assign($resolverOne);

        $this->assertEquals($resolverOne, $ticket->resolver);

        $ticket->assign($resolverTwo);
        $this->assertEquals($resolverTwo, $ticket->resolver);
        $this->assertNotEquals($resolverOne, $ticket->resolver);
    }

    function testOnlyResolverWithPermissionCanChangeTicketPriority()
    {
        $resolverWithPermission = Resolver::factory(['can_change_priority' => 1])->create();
        $resolverWithoutPermission = Resolver::factory()->create();

        $this->actingAs($resolverWithPermission);

        $ticket = Ticket::factory(['resolver_id' => $resolverWithPermission])->create();

        $this->assertTrue($ticket->setPriority(1));

        $this->actingAs($resolverWithoutPermission);

        $this->expectException(HttpException::class);

        $ticket->setPriority(1);
    }

    function testTicketsIndexShowsTicketsCorrectly(){
        Ticket::truncate();

        $this->actingAs(User::factory()->create());

        $userOne = User::factory(['name' => 'John'])->create();
        $resolverOne = Resolver::factory(['name' => 'Thomas'])->create();

        Ticket::factory([
            'description' => 'Ticket 1 description',
            'user_id' => $userOne,
            'resolver_id' => $resolverOne,
        ])->create();

        $response = $this->get(route('tickets.index'));
        $response->assertSee('Ticket 1 description');
        $response->assertDontSee('Ticket 2 description');
        $response->assertSee('John');
        $response->assertSee('Thomas');
        $response->assertDontSee('Alex');

        $userTwo = User::factory(['name' => 'Alex'])->create();
        $resolverTwo = Resolver::factory(['name' => 'Jane'])->create();

        Ticket::factory([
            'description' => 'Ticket 2 description',
            'user_id' => $userTwo,
            'resolver_id' => $resolverTwo,
        ])->create();

        $response = $this->get(route('tickets.index'));
        $response->assertSee('Ticket 1 description');
        $response->assertSee('Ticket 2 description');
        $response->assertDontSee('Ticket 3 description');
        $response->assertSee('John');
        $response->assertSee('Thomas');
        $response->assertSee('Alex');
        $response->assertSee('Jane');
    }

    function testTicketsIndexPaginationShowsCorrectNumberOfTickets(){
        Ticket::truncate();

        $this->actingAs(User::factory()->create());

        Ticket::factory(Ticket::DEFAULT_PAGINATION)->create();
        Ticket::factory(['description' =>
            $description = 'This ticket is supposed to be on second pagination page'
        ])->create();

        $response = $this->get(route('tickets.index', ['page' => 1]));
        $response->assertDontSee($description);

        $response = $this->get(route('tickets.index', ['page' => 2]));
        $response->assertSee($description);
    }
}
