<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Request;
use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function test_it_belongs_to_many_resolvers()
    {
        User::factory(['name' => 'John Doe'])->resolver(true)->create();
        User::factory(['name' => 'Frank Loew'])->resolver(true)->create();
        $group = Group::firstOrFail();


        $this->assertEquals('John Doe', $group->resolvers()->first()->name);
        $this->assertEquals('Frank Loew', $group->resolvers()->orderByDesc('id')->first()->name);
    }

    public function test_it_has_many_tickets(){
        $group = Group::firstOrFail();

        Ticket::factory(2, ['group_id' => $group])->create();

        $this->assertCount(2, $group->tickets);
    }

    public function test_it_has_many_requests(){
        $group = Group::firstOrFail();
        Request::factory(2, ['group_id' => $group])->create();

        $this->assertCount(2, $group->requests);
    }
}
