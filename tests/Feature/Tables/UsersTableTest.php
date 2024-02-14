<?php

namespace Tables;

use App\Enums\Priority;
use App\Helpers\Table\Table;
use App\Livewire\Tables\TasksTable;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsersTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_renders_headers_in_correct_order()
    {
        //
    }

    /** @test */
    function it_renders_data_in_correct_order()
    {
        //
    }

    /** @test */
    function it_renders_users_in_descending_order_by_email_address_property()
    {
        //
    }

    /** @test */
    function it_renders_users_in_ascending_order_by_email_address_property_if_wire_click_on_email_property()
    {
        //
    }

    /** @test */
    function it_renders_users_in_descending_order_by_email_address_property_if_wire_click_on_email_property_twice()
    {
        //
    }

    /** @test */
    function it_renders_users_in_descending_order_by_name_address_property_if_wire_click_on_name_property()
    {
        //
    }

    /** @test */
    function it_filters_users_based_on_text_input_in_email_column()
    {
        //
    }

    /** @test */
    function it_renders_users_based_on_table_pagination_input_field()
    {
        //
    }

    /** @test */
    function it_paginates_25_users_by_default()
    {
        //
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
        //
    }
}

