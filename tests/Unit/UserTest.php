<?php

namespace Tests\Unit;

use App\Enums\ResolverPanelOption;
use App\Models\FavoriteResolverPanelOption;
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

    /** @test */
    function it_belongs_to_many_groups()
    {
        $groupOne = Group::factory(['name' => 'TEST-GROUP-1'])->create();
        $groupTwo = Group::factory(['name' => 'TEST-GROUP-2'])->create();
        $resolver = User::factory()->create();
        $resolver->groups()->attach($groupOne);
        $resolver->groups()->attach($groupTwo);

        $this->assertCount(2, $resolver->groups);
    }

    /** @test */
    function it_as_caller_has_many_requests(){
        $caller = User::factory()->create();
        Request::factory(2, ['caller_id' => $caller])->create();

        $this->assertCount(2, $caller->requests);
    }

    /** @test */
    function it_as_resolver_has_many_requests(){
        $resolver = User::factory()->resolver()->create();
        Request::factory(2, ['resolver_id' => $resolver])->create();

        $this->assertCount(2, $resolver->resolverRequests);
    }

    /** @test */
    function only_one_resolver_can_be_assigned_to_ticket()
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

    /** @test */
    function it_has_many_favorite_resolver_panel_options()
    {
        $user = User::factory()->create();
        FavoriteResolverPanelOption::factory([
            'user_id' => $user,
            'option' => ResolverPanelOption::INCIDENTS,
        ])->create();
        FavoriteResolverPanelOption::factory([
            'user_id' => $user,
            'option' => ResolverPanelOption::REQUESTS,
        ])->create();

        $this->assertCount(2, $user->favoriteResolverPanelOptions);
    }

    /** @test */
    function it_has_correct_default_profile_picture()
    {
        $user = User::factory()->create();

        $this->assertEquals(User::DEFAULT_PROFILE_PICTURE, $user->profile_picture);
    }

    /** @test */
    function it_has_profile_picture()
    {
        $user = User::factory(['profile_picture' => 'j2dku8ds.jpg'])->create();

        $this->assertEquals('j2dku8ds.jpg', $user->profile_picture);
    }

    /** @test */
    function resolver_does_not_have_set_priority_permission()
    {
        $resolver = User::factory()->resolver()->create();

        $this->assertFalse($resolver->hasPermissionTo('set_priority'));
    }

    /** @test */
    function manager_has_set_priority_permission()
    {
        $manager = User::factory()->manager()->create();

        $this->assertTrue($manager->hasPermissionTo('set_priority'));
    }
}
