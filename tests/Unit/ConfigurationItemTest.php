<?php


use App\Enums\ConfigurationItemStatus;
use App\Enums\ConfigurationItemType;
use App\Enums\Location;
use App\Enums\OperatingSystem;
use App\Models\ConfigurationItem;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfigurationItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_serial_number()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->assertNotNull($configurationItem->serial_number);
    }

    /** @test */
    function it_belongs_to_group()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->assertInstanceOf(Group::class, $configurationItem->group);
    }

    /** @test */
    function it_belongs_to_user()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->assertInstanceOf(User::class, $configurationItem->user);
    }

    /** @test */
    function it_has_location_enum()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->assertInstanceOf(Location::class, $configurationItem->location);
    }

    /** @test */
    function it_has_operating_system_enum()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->assertInstanceOf(OperatingSystem::class, $configurationItem->operating_system);
    }

    /** @test */
    function it_has_configuration_item_type_enum()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->assertInstanceOf(ConfigurationItemType::class, $configurationItem->type);
    }

    /** @test */
    function it_has_configuration_item_status_enum()
    {
        $configurationItem = ConfigurationItem::factory()->create();

        $this->assertInstanceOf(ConfigurationItemStatus::class, $configurationItem->status);
    }
}
