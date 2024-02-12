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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use ValueError;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function resolver_can_set_status()
    {
        $resolver = User::factory()->resolverAllGroups()->create();
        $configurationItem = ConfigurationItem::factory()->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('status', ConfigurationItemStatus::IN_STOCK->value)
            ->call('save');

        $this->assertDatabaseHas('configuration_items', [
            'id' => $configurationItem->id,
            'status' => ConfigurationItemStatus::IN_STOCK,
        ]);
    }

    static function invalidData(){
        return [
            ['location', 'word',],
            ['location', '',],
            ['status', 'word',],
            ['status', '',],
            ['type', 'word',],
            ['type', '',],
            ['operatingSystem', 'word',],
            ['operatingSystem', '',],
        ];
    }

    /**
     * @dataProvider invalidData
     * @test
     */
    function it_throws_value_error_when_invalid_data_set_for_enums($property, $value)
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory()->create();

        $this->expectException(ValueError::class);

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set($property, $value);
    }

    /** @test */
    function resolver_is_able_to_set_specified_properties()
    {
        $resolver = User::factory()->resolver()->create();
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $configurationItem = ConfigurationItem::factory()->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('group', $group->id)
            ->set('location', LOCATION::MICHIGAN->value)
            ->set('status', ConfigurationItemStatus::IN_STOCK->value)
            ->set('type', ConfigurationItemType::SHOP_FLOOR->value)
            ->set('operatingSystem', OperatingSystem::LINUX->value)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('configuration_items', [
            'id' => $configurationItem->id,
            'group_id' => $group->id,
            'location' => Location::MICHIGAN->value,
            'status' => ConfigurationItemStatus::IN_STOCK->value,
            'type' => ConfigurationItemType::SHOP_FLOOR->value,
            'operating_system' => OperatingSystem::LINUX->value,
        ]);
    }

    /** @test */
    function it_updates_configuration_item_when_correct_data_submitted()
    {
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory()->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('group', $group->id)
            ->set('location', Location::MICHIGAN->value)
            ->set('status', ConfigurationItemStatus::INSTALLED->value)
            ->set('type', ConfigurationItemType::LAB_TEST->value)
            ->set('operatingSystem', OperatingSystem::LINUX->value)
            ->call('save');

        $this->assertDatabaseHas('configuration_items', [
            'id' => $configurationItem->id,
            'group_id' => $group->id,
            'location' => Location::MICHIGAN->value,
            'status' => ConfigurationItemStatus::INSTALLED->value,
            'type' => ConfigurationItemType::LAB_TEST->value,
            'operating_system' => OperatingSystem::LINUX->value,
        ]);
    }

    /** @test */
    function resolver_can_add_comment(){
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('comment', 'Test comment')
            ->call('save');

        Livewire::test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->assertSee('Test comment');

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $configurationItem->id,
            'causer_id' => $resolver->id,
            'event' => 'comment',
            'description' => 'Test comment'
        ]);
    }

    /** @test */
    function operating_system_property_cannot_be_modified_if_status_is_retired()
    {
        $resolver = User::factory()->resolver()->create();
        $configurationItem = ConfigurationItem::factory([
            'status' => ConfigurationItemStatus::RETIRED,
            'operating_system' => OperatingSystem::LINUX,
        ])->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemEditForm::class, ['configurationItem' => $configurationItem])
            ->set('operatingSystem', OperatingSystem::WINDOWS_10->value)
            ->assertForbidden();
    }
}
