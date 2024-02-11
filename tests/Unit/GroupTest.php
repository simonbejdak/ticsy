<?php

namespace Tests\Unit;

use App\Models\ConfigurationItem;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Request;
use App\Models\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function test_it_belongs_to_many_resolvers()
    {
        User::factory(['name' => 'John Doe'])->resolverAllGroups()->create();
        User::factory(['name' => 'Frank Loew'])->resolverAllGroups()->create();
        $group = Group::firstOrFail();


        $this->assertEquals('John Doe', $group->resolvers()->first()->name);
        $this->assertEquals('Frank Loew', $group->resolvers()->orderByDesc('id')->first()->name);
    }

    public function test_it_has_many_incidents(){
        $group = Group::firstOrFail();

        Incident::factory(2, ['group_id' => $group])->create();

        $this->assertCount(2, $group->incidents);
    }

    public function test_it_has_many_requests(){
        $group = Group::firstOrFail();
        Request::factory(2, ['group_id' => $group])->create();

        $this->assertCount(2, $group->requests);
    }

    /** @test */
    function it_has_many_configuration_items()
    {
        $group = Group::factory()->create();
        ConfigurationItem::factory(2, ['group_id' => $group])->create();

        $this->assertInstanceOf(ConfigurationItem::class, $group->configurationItems()->first());
        $this->assertCount(2, $group->configurationItems);
    }

}
