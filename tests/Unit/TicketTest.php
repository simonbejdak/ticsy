<?php


namespace Tests\Unit;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    function test_it_has_type_relationship()
    {
        $type = Type::factory(['name' => 'incident'])->create();
        $ticket = Ticket::factory(['type_id' => $type])->create();

        $this->assertEquals('Incident', $ticket->type->name);
    }

    function test_it_has_category_relationship()
    {
        $category = Category::factory(['name' => 'network'])->create();
        $ticket = Ticket::factory(['category_id' => $category])->create();

        $this->assertEquals('network', $ticket->category->name);
    }

    function test_it_has_resolver_relationship(){
        $resolver = User::factory(['name' => 'John Doe'])->resolver()->create();
        $ticket = Ticket::factory(['resolver_id' => $resolver])->create();

        $this->assertEquals('John Doe', $ticket->resolver->name);
    }

    function test_it_has_priority()
    {
        $ticket = Ticket::factory(['priority' => 4])->create();

        $this->assertEquals(4, $ticket->priority);
    }

    function test_it_has_description()
    {
        $ticket = Ticket::factory(['description' => 'Ticket Description'])->create();

        $this->assertEquals('Ticket Description', $ticket->description);
    }

    function test_sql_violation_thrown_when_higher_priority_than_predefined_is_assigned()
    {
        $this->expectException(QueryException::class);

        Ticket::factory(['priority' => count(Ticket::PRIORITIES) + 1])->create();
    }

    function test_it_has_correct_default_priority()
    {
        $ticket = new Ticket();

        $this->assertEquals(Ticket::DEFAULT_PRIORITY, $ticket->priority);
    }
}
