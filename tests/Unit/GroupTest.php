<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function test_it_has_belongs_to_many_resolvers()
    {
        $group = Group::factory()->create();

        $resolverOne = User::factory(['name' => 'John Doe'])->create()->assignRole('resolver');
        $group->resolvers()->attach($resolverOne);

        $resolverTwo = User::factory(['name' => 'Frank Loew'])->create()->assignRole('resolver');
        $group->resolvers()->attach($resolverTwo);

        $this->assertEquals('John Doe', $group->resolvers()->first()->name);
        $this->assertEquals('Frank Loew', $group->resolvers()->orderByDesc('id')->first()->name);
    }

    public function test_it_has_has_many_tickets_relationship(){
        $group = Group::factory()->create();

        $ticketOne = Ticket::factory([
            'group_id' => $group,
            'description' => 'Ticket 1 description'
        ])->create();

        $ticketTwo = Ticket::factory([
            'group_id' => $group,
            'description' => 'Ticket 2 description'
        ])->create();

        $i = 1;
        foreach ($group->tickets as $ticket){
            $this->assertEquals('Ticket ' . $i . ' description', $ticket->description);
            $i++;
        }
    }
}
