<?php


use App\Enums\ResolverPanelOption;
use App\Models\ConfigurationItem;
use App\Models\FavoriteResolverPanelOption;
use App\Models\Group;
use App\Models\Incident;
use App\Models\Request;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfigurationItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_serial_number()
    {
        $configuration_item = ConfigurationItem::factory()->create();
        $this->assertNotNull($configuration_item->serial_number);
    }

    /** @test */
    function it_belongs_to_group()
    {
        $configuration_item = ConfigurationItem::factory()->create();
        $this->assertInstanceOf(Group::class, $configuration_item->group);
    }

    /** @test */
    function it_belongs_to_primary_user()
    {
        //
    }

    /** @test */
    function it_has_location_enum()
    {

    }

    /** @test */
    function it_has_operating_system_enum()
    {
        //
    }

    /** @test */
    function it_has_configuration_item_type_enum()
    {
        //
    }

    /** @test */
    function it_has_configuration_item_status_enum()
    {
        //
    }


}
