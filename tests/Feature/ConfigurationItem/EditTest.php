<?php


namespace Tests\Feature\ConfigurationItem;

use App\Enums\ConfigurationItemStatus;
use App\Enums\ConfigurationItemType;
use App\Enums\Location;
use App\Enums\OperatingSystem;
use App\Models\ConfigurationItem;
use App\Models\Group;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_redirect_guests_to_login_page()
    {
        $configurationItem = ConfigurationItem::factory()->create();
        $response = $this->get(route('configuration-items.edit', $configurationItem->id));

        $response->assertRedirectToRoute('login');
    }

    /** @test */
    function it_errors_to_403_to_unauthorized_users()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->actingAs(User::factory()->create());
        $response = $this->get(route('configuration-items.edit', $configurationItem->id));

        $response->assertForbidden();
    }

    /** @test */
    function it_authorizes_resolver_to_view(){
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('configuration-items.edit', $configurationItem));
        $response->assertSuccessful();
    }

    /** @test */
    function it_displays_configuration_item_data()
    {
        $serialNumber = 'PLT73816';
        $location = Location::DOLNY_KUBIN;
        $operatingSystem = OperatingSystem::WINDOWS_7;
        $status = ConfigurationItemStatus::INSTALLED;
        $type = ConfigurationItemType::SECONDARY;
        $group = Group::firstOrFail();
        $user = User::factory()->create();

        $configurationItem = ConfigurationItem::factory([
            'serial_number' => $serialNumber,
            'location' => $location,
            'operating_system' => $operatingSystem,
            'status' => $status,
            'type' => $type,
            'group_id' => $group->id,
            'user_id' => $user->id,
        ])->create();


        $this->actingAs(User::factory()->resolver()->create());

        $response = $this->get(route('configuration-items.edit', $configurationItem->id));
        $response->assertSuccessful();
        $response->assertSee($serialNumber);
        $response->assertSee($location->value);
        $response->assertSee($operatingSystem->value);
        $response->assertSee($status->name);
        $response->assertSee($type->value);
        $response->assertSee($group->name);
        $response->assertSee($user->name);
    }

    /** @test */
    function it_displays_comments()
    {
        $resolver = User::factory()->resolver()->create();
        $configuration_item = ConfigurationItem::factory()->create();

        $this->actingAs($resolver);
        ActivityService::comment($configuration_item, 'Comment Body');

        $this->get(route('configuration-items.edit', $configuration_item))
            ->assertSuccessful()
            ->assertSee('Comment Body');
    }

    /** @test */
    function it_displays_incident_created_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder([
//                'Status:', 'Open',
//                'Priority', '4',
//                'Group:', 'SERVICE-DESK',
//            ]);
    }

    /** @test */
    function it_displays_changes_activity_dynamically()
    {
//        $resolver = User::factory()->resolverAllGroups()->create();
//        $incident = Incident::factory(['status' => Status::OPEN])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('status', Status::IN_PROGRESS->value)
//            ->set('resolver', $resolver->id)
//            ->call('save')
//            ->assertSuccessful();
//
//        $incident = $incident->refresh();
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    function it_displays_multiple_activity_changes()
    {
//        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
//        $resolver = User::factory()->resolverAllGroups()->create();
//        $incident = Incident::factory([
//            'status' => Status::OPEN,
//            'group_id' => Group::SERVICE_DESK_ID,
//        ])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('status', Status::IN_PROGRESS->value)
//            ->set('group', $group->id)
//            ->set('resolver', $resolver->id)
//            ->call('save')
//            ->assertSuccessful();
//
//        $incident = $incident->refresh();
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open'])
//            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    function it_displays_status_changes_activity()
    {
//        $resolver = User::factory()->resolverAllGroups()->create();
//        $incident = Incident::factory(['status' => Status::OPEN])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('status', Status::IN_PROGRESS->value)
//            ->set('resolver', $resolver->id)
//            ->call('save')
//            ->assertSuccessful();
//
//        $incident = $incident->refresh();
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    function it_displays_on_hold_reason_changes_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('status', Status::ON_HOLD->value)
//            ->set('onHoldReason', OnHoldReason::CALLER_RESPONSE->value)
//            ->set('comment', 'Test comment')
//            ->call('save')
//            ->assertSuccessful();
//
//        $incident = $incident->refresh();
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['On hold reason:', 'Caller Response', 'was', 'empty']);
    }

    /** @test */
    function it_displays_priority_changes_activity()
    {
//        $manager = User::factory()->manager()->create();
//        $incident = Incident::factory(['priority' => Incident::DEFAULT_PRIORITY])->create();
//
//        Livewire::actingAs($manager);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('priority', 3)
//            ->set('comment', 'Production issue')
//            ->call('save')
//            ->assertSuccessful();
//
//        $incident = $incident->refresh();
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Priority:', '3', 'was', Incident::DEFAULT_PRIORITY]);
    }

    /** @test */
    function it_displays_group_changes_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
//        $incident = Incident::factory(['group_id' => Group::SERVICE_DESK_ID])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('group', $group->id)
//            ->call('save')
//            ->assertSuccessful();
//
//        $incident = $incident->refresh();
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    function it_displays_resolver_changes_activity()
    {
//        $resolver = User::factory(['name' => 'Average Joe'])->resolverAllGroups()->create();
//        $incident = Incident::factory()->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('resolver', $resolver->id)
//            ->call('save')
//            ->assertSuccessful();
//
//        $incident = $incident->refresh();
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Resolver:', 'Average Joe', 'was', 'empty']);
    }

    /** @test */
    function it_displays_activities_in_descending_order()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->create();
//
//        $incident->status = Status::IN_PROGRESS;
//        $incident->save();
//
//        ActivityService::comment($incident, 'Test Comment');
//
//        $incident->status = Status::MONITORING;
//        $incident->save();
//
//        $incident->refresh();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSeeInOrder([
//                'Status:', 'Monitoring', 'was', 'In Progress',
//                'Test Comment',
//                'Status:', 'In Progress', 'was', 'Open',
//                'Created', 'Status:', 'Open',
//            ]);
    }

    /** @test */
    function it_requires_comment_if_priority_changes()
    {
//        $incident = Incident::factory()->create();
//        $manager = User::factory()->manager()->create();
//
//        Livewire::actingAs($manager);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('priority', 3)
//            ->call('save')
//            ->assertHasErrors(['comment' => 'required'])
//            ->set('comment', 'Production issue')
//            ->call('save')
//            ->assertSuccessful();
    }

    /** @test */
    function sla_bar_shows_correct_minutes()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->create();
//
//        $date = Carbon::now()->addMinutes(10);
//        Carbon::setTestNow($date);
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSee($incident->sla->minutesTillExpires() . ' minutes');
    }

    /** @test */
    function it_allows_to_add_comment_to_caller()
    {
//        $caller = User::factory()->create();
//        $incident = Incident::factory(['caller_id' => $caller])->create();
//
//        Livewire::actingAs($caller);
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('comment', 'Test comment')
//            ->call('save');
//
//        Livewire::test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSee('Test comment');
//
//        $this->assertDatabaseHas('activity_log', [
//            'subject_id' => $incident->id,
//            'causer_id' => $caller->id,
//            'event' => 'comment',
//            'description' => 'Test comment'
//        ]);
    }

    /** @test */
    function resolver_is_required_if_status_in_progress()
    {
//        $incident = Incident::factory()->create();
//        $resolver = User::factory()->resolver()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('status', Status::IN_PROGRESS->value)
//            ->call('save')
//            ->assertHasErrors(['resolver' => 'required']);
    }

    /** @test */
    function resolver_set_to_null_if_status_is_open()
    {
//        $resolver = User::factory()->resolverAllGroups()->create();
//        $incident = Incident::factory(['resolver_id' => $resolver])->statusInProgress()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->assertSet('resolver', $resolver->id)
//            ->set('status', Status::OPEN->value)
//            ->assertSet('resolver', null);
    }

    /** @test */
    function it_does_not_require_comment_if_status_is_on_hold_and_status_was_not_changed()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->statusOnHold()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->call('save')
//            ->assertHasNoErrors(['comment', 'required']);
    }

    /** @test */
    function it_does_not_require_comment_if_status_is_resolved_and_status_was_not_changed()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->statusResolved()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->call('save')
//            ->assertHasNoErrors(['comment', 'required']);
    }

    /** @test */
    function it_does_not_require_comment_if_status_is_cancelled_and_status_was_not_changed()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->statusCancelled()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->call('save')
//            ->assertHasNoErrors(['comment', 'required']);
    }

    /** @test */
    function resolver_cannot_change_priority()
    {
//        $resolver = User::factory()->resolver()->create();
//        $incident = Incident::factory()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('priority', Priority::THREE->value)
//            ->assertForbidden();
    }

    /** @test */
    function manager_can_change_priority()
    {
//        $manager = User::factory()->manager()->create();
//        $incident = Incident::factory()->create();
//
//        Livewire::actingAs($manager)
//            ->test(IncidentEditForm::class, ['incident' => $incident])
//            ->set('priority', Priority::THREE->value)
//            ->set('comment', 'Production Issue')
//            ->call('save')
//            ->assertSuccessful();
//
//        $this->assertDatabaseHas('incidents', [
//            'id' => $incident->id,
//            'priority' => Priority::THREE->value,
//        ]);
    }
}
