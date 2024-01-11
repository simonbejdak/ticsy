<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\Incident;
use App\Models\Request;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    function test_it_belongs_to_many_groups()
    {
        $groupOne = Group::factory(['name' => 'TEST-GROUP-1'])->create();
        $groupTwo = Group::factory(['name' => 'TEST-GROUP-2'])->create();
        $resolver = User::factory()->create();
        $resolver->groups()->attach($groupOne);
        $resolver->groups()->attach($groupTwo);

        $this->assertCount(2, $resolver->groups);
    }

    function test_it_as_caller_has_many_requests(){
        $caller = User::factory()->create();
        Request::factory(2, ['caller_id' => $caller])->create();

        $this->assertCount(2, $caller->requests);
    }

    function test_it_as_resolver_has_many_requests(){
        $resolver = User::factory()->resolver()->create();
        Request::factory(2, ['resolver_id' => $resolver])->create();

        $this->assertCount(2, $resolver->resolverRequests);
    }

    function test_only_one_resolver_can_be_assigned_to_ticket()
    {
        $incident = Incident::factory()->create();
        $resolverOne = User::factory()->resolver()->create();
        $resolverTwo = User::factory()->resolver()->create();

        TicketService::assignTicket($incident, $resolverOne);
        $incident->refresh();

        $this->assertEquals($resolverOne->id, $incident->resolver_id);

        TicketService::assignTicket($incident, $resolverTwo);
        $incident->refresh();

        $this->assertEquals($resolverTwo->id, $incident->resolver_id);
        $this->assertNotEquals($resolverOne->id, $incident->resolver_id);
    }

    public function test_it_has_correct_default_profile_picture()
    {
        $user = User::factory()->create();

        $this->assertEquals(User::DEFAULT_PROFILE_PICTURE, $user->profile_picture);
    }

    public function test_it_has_profile_picture()
    {
        $user = User::factory(['profile_picture' => 'j2dku8ds.jpg'])->create();

        $this->assertEquals('j2dku8ds.jpg', $user->profile_picture);
    }
}
