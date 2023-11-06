<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function test_it_belongs_to_many_resolvers()
    {
        $group = Group::firstOrFail();

        User::factory(['name' => 'John Doe'])
            ->hasAttached($group)
            ->create()
            ->assignRole('resolver');

        User::factory(['name' => 'Frank Loew'])
            ->hasAttached($group)
            ->create()
            ->assignRole('resolver');

        $this->assertEquals('John Doe', $group->resolvers()->first()->name);
        $this->assertEquals('Frank Loew', $group->resolvers()->orderByDesc('id')->first()->name);
    }

    public function test_it_has_many_tickets(){
        $group = Group::firstOrFail();

        Ticket::factory([
            'group_id' => $group,
            'description' => 'Ticket 1 description'
        ])->create();

        Ticket::factory([
            'group_id' => $group,
            'description' => 'Ticket 2 description'
        ])->create();

        $this->assertEquals('Ticket 1 description', $group->tickets()->first()->description);
        $this->assertEquals('Ticket 2 description', $group->tickets()->orderByDesc('id')->first()->description);
    }
}
