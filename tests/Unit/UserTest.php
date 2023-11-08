<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    function test_it_belongs_to_many_groups()
    {
        $group = Group::findOrFail(Group::LOCAL_6445_NEW_YORK);
        $resolver = User::factory()->resolver(true)->create();

        // by default resolver in tests always belongs to the default group
        $this->assertEquals(Group::DEFAULT, $resolver->groups()->findOrFail(1)->id);
        $this->assertEquals(Group::LOCAL_6445_NEW_YORK, $resolver->groups()->findOrFail(2)->id);
    }

    function test_it_has_many_comments()
    {
        $user = User::factory()->create();

        $commentOne = Comment::factory([
            'user_id' => $user,
            'body' => 'Comment Body 1',
        ])->create();

        $commentTwo = Comment::factory([
            'user_id' => $user,
            'body' => 'Comment Body 2',
        ])->create();

        $i = 1;
        foreach ($user->comments as $comment){
            $this->assertEquals('Comment Body ' . $i, $comment->body);
            $i++;
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
