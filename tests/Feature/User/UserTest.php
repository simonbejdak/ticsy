<?php

namespace User;

use App\Models\Group;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    function test_it_can_belong_to_multiple_groups()
    {
        $groupOne = Group::factory(['name' => 'Group 0'])->create();
        $groupTwo = Group::factory(['name' => 'Group 1'])->create();
        $resolver = User::factory()->resolver()->create();

        $resolver->groups()->attach($groupOne);
        $resolver->groups()->attach($groupTwo);

        $groups = $resolver->groups;

        for($i = 0; $i <= count($groups) - 1; $i++){
            $this->assertEquals('Group ' . $i, $groups[$i]->name);
        }
    }

    function test_only_one_resolver_can_be_assigned_to_ticket()
    {
        $ticket = Ticket::factory()->create();
        $resolverOne = User::factory()->resolver()->create();
        $resolverTwo = User::factory()->resolver()->create();

        $ticket->assign($resolverOne);

        $this->assertEquals($resolverOne, $ticket->resolver);

        $ticket->assign($resolverTwo);
        $this->assertEquals($resolverTwo, $ticket->resolver);
        $this->assertNotEquals($resolverOne, $ticket->resolver);
    }
}
