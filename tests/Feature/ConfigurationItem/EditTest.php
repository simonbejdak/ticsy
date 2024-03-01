<?php


namespace Tests\Feature\ConfigurationItem;

use App\Enums\ConfigurationItemStatus;
use App\Enums\ConfigurationItemType;
use App\Enums\Location;
use App\Enums\OperatingSystem;
use App\Livewire\ConfigurationItemEditForm;
use App\Models\ConfigurationItem;
use App\Models\Group;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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
        $response->assertSee($status->value);
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
    function it_displays_configuration_created_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory()->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder([
                'Location:', 'Námestovo',
                'Status:', 'Installed',
                'Type:', 'Primary',
                'Operating system:', 'Windows 10',
            ]);
    }

    /** @test */
    function it_displays_changes_activity_dynamically()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory(['status' => ConfigurationItemStatus::IN_STOCK])->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('status', ConfigurationItemStatus::INSTALLED->value)
            ->call('save')
            ->assertSuccessful();

        $configurationItem = $configurationItem->refresh();

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'Installed', 'was', 'In Stock']);
    }

    /** @test */
    function it_displays_multiple_activity_changes()
    {
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory([
            'status' => ConfigurationItemStatus::IN_STOCK,
            'group_id' => Group::SERVICE_DESK_ID,
        ])->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('status', ConfigurationItemStatus::INSTALLED->value)
            ->set('group', $group->id)
            ->call('save')
            ->assertSuccessful();

        $configurationItem = $configurationItem->refresh();

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'Installed', 'was', 'In Stock'])
            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    function it_displays_group_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory(['group_id' => Group::SERVICE_DESK_ID])->create();
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('group', $group->id)
            ->call('save')
            ->assertSuccessful();

        $configurationItem = $configurationItem->refresh();

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);    }

    /** @test */
    function it_displays_status_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory(['status' => ConfigurationItemStatus::IN_STOCK])->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('status', ConfigurationItemStatus::INSTALLED->value)
            ->call('save')
            ->assertSuccessful();

        $configurationItem = $configurationItem->refresh();

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'Installed', 'was', 'In Stock']);
    }

    /** @test */
    function it_displays_location_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory(['location' => Location::DOLNY_KUBIN])->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('location', Location::NAMESTOVO->value)
            ->call('save')
            ->assertSuccessful();

        $configurationItem = $configurationItem->refresh();

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder(['Location:', 'Námestovo', 'was', 'Dolný Kubín']);
    }

    /** @test */
    function it_displays_type_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory(['type' => ConfigurationItemType::SECONDARY])->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('type', ConfigurationItemType::PRIMARY->value)
            ->call('save')
            ->assertSuccessful();

        $configurationItem = $configurationItem->refresh();

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder(['Type:', 'Primary', 'was', 'Secondary']);
    }

    /** @test */
    function it_displays_operating_system_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory(['operating_system' => OperatingSystem::WINDOWS_7])->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('operatingSystem', OperatingSystem::WINDOWS_10->value)
            ->call('save')
            ->assertSuccessful();

        $configurationItem = $configurationItem->refresh();

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSuccessful()
            ->assertSeeInOrder(['Operating system:', 'Windows 10', 'was', 'Windows 7']);
    }

    /** @test */
    function it_displays_activities_in_descending_order()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory(['status' => ConfigurationItemStatus::INSTALLED])->create();

        $configurationItem->status = ConfigurationItemStatus::IN_STOCK;
        $configurationItem->save();

        ActivityService::comment($configurationItem, 'Test Comment');

        $configurationItem->status = ConfigurationItemStatus::RETIRED;
        $configurationItem->save();

        $configurationItem->refresh();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSeeInOrder([
                'Status:', 'Retired', 'was', 'In Stock',
                'Test Comment',
                'Status:', 'In Stock', 'was', 'Installed',
                'Created', 'Status:', 'Installed',
            ]);
    }

}
