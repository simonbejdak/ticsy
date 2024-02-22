<?php

namespace Tables;

use App\Enums\Priority;
use App\Livewire\Table;
use App\Livewire\Tables\ConfigurationItemsTable;
use App\Livewire\Tables\TasksTable;
use App\Models\ConfigurationItem;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ConfigurationItemsTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider headers
     * @test
     */
    function it_renders_headers_in_correct_order($headers)
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.configuration-items'));

        $response->assertSeeInOrder($headers);
    }

    /** @test */
    function it_renders_data_in_correct_order()
    {
        $configurationItems = ConfigurationItem::factory(3)->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.configuration-items'));
        foreach ($configurationItems as $configurationItem) {
            $response->assertSeeInOrder([
                $configurationItem->serial_number,
                $configurationItem->user->name,
                $configurationItem->location,
            ]);
        }
    }

    /** @test */
    function it_renders_configuration_items_in_descending_order_by_default()
    {
        $configurationItemOne = ConfigurationItem::factory()->create();
        $configurationItemTwo = ConfigurationItem::factory()->create();
        $configurationItemThree = ConfigurationItem::factory()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.configuration-items'));

        $response->assertSeeInOrder([
            $configurationItemThree->serial_number,
            $configurationItemTwo->serial_number,
            $configurationItemOne->serial_number,
        ]);
    }

    /** @test */
    function it_renders_configuration_items_in_ascending_order_if_wire_click_on_serial_number_header()
    {
        $configurationItemOne = Task::factory()->started()->create();
        $configurationItemTwo = Task::factory()->started()->create();
        $configurationItemThree = Task::factory()->started()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'serial_number')
            ->assertSeeInOrder([
                $configurationItemOne->caller->name,
                $configurationItemTwo->caller->name,
                $configurationItemThree->caller->name
            ]);
    }

    /** @test */
    function it_renders_configuration_items_in_descending_order_if_wire_click_twice_on_number_header()
    {
        $configurationItemOne = Task::factory()->started()->create();
        $configurationItemTwo = Task::factory()->started()->create();
        $configurationItemThree = Task::factory()->started()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TasksTable::class)
            ->call('columnHeaderClicked', 'serial_number')
            ->call('columnHeaderClicked', 'serial_number')
            ->assertSeeInOrder([
                $configurationItemThree->caller->name,
                $configurationItemTwo->caller->name,
                $configurationItemOne->caller->name,
            ]);
    }

    /** @test */
    function it_filters_configuration_items_based_on_serial_number_search_text_input()
    {
        $resolver = User::factory()->resolver()->create();
        ConfigurationItem::factory(['serial_number' => '1234'])->create();
        ConfigurationItem::factory(['serial_number' => '5678'])->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemsTable::class)
            ->assertSee('1234')
            ->assertSee('5678')
            ->set('searchCases.serial_number', '1234')
            ->assertSee('1234')
            ->assertDontSee('5678')
            ->set('searchCases.serial_number', '5678')
            ->assertDontSee('1234')
            ->assertSee('5678')
            ->set('searchCases.serial_number', 'trt')
            ->assertDontSee('1234')
            ->assertDontSee('5678');
    }

    /** @test */
    function it_paginates_25_configuration_items_per_page_by_default()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of tasks should be displayed on the first page
        // PG2 stands for page 2
        ConfigurationItem::factory(25, ['serial_number' => 'PG2'])->create();
        ConfigurationItem::factory(25, ['serial_number' => 'PG1'])->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemsTable::class)
            ->assertSee('PG1')
            ->assertDontSee('PG2');
    }

    /** @test */
    function it_renders_configuration_items_based_on_pagination_index_input_field()
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of tasks should be displayed on the first page
        // TSTCI stands for Test Configuration item
        ConfigurationItem::factory(Table::DEFAULT_ITEMS_PER_PAGE)->create();
        ConfigurationItem::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['serial_number' => 'TSTCI'])->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemsTable::class)
            ->assertSee('TSTCI')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('TSTCI')
            ->set('paginationIndex', 1)
            ->assertSee('TSTCI');
    }

    static function invalidPaginationIndexInput(): array
    {
        return [
            [0],
            [(Table::DEFAULT_ITEMS_PER_PAGE * 2) + 1],
            ['zero'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidPaginationIndexInput
     */
    function it_renders_first_pagination_page_if_pagination_index_input_is_invalid($invalidInput)
    {
        $resolver = User::factory()->resolver()->create();
        // Order is supposed to be descendant by default, therefore second bulk of tasks should be displayed on the first page
        ConfigurationItem::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['serial_number' => 'CI2'])->create();
        ConfigurationItem::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['serial_number' => 'CI1'])->create();

        Livewire::actingAs($resolver)
            ->test(ConfigurationItemsTable::class)
            ->assertSee('CI1')
            ->assertDontSee('CI2')
            ->set('paginationIndex', Table::DEFAULT_ITEMS_PER_PAGE + 1)
            ->assertDontSee('CI1')
            ->assertSee('CI2')
            ->set('paginationIndex', $invalidInput)
            ->assertSee('CI1')
            ->assertDontSee('CI2');
    }

    static function headers(): array
    {
        return [
            [
                ['Serial Number', 'User', 'Location']
            ]
        ];
    }
}

