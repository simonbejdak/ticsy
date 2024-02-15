<?php

namespace Tables;

use App\Enums\Location;
use App\Enums\Priority;
use App\Enums\UserStatus;
use App\Helpers\Table\Table;
use App\Livewire\Tables\TasksTable;
use App\Livewire\Tables\UsersTable;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsersTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_renders_successfully()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('resolver-panel.users'));
        $response->assertSuccessful();
        $response->assertSeeLivewire(UsersTable::class);

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->assertSuccessful();
    }

    static function headers(): array
    {
        return [
            [
                ['E-mail', 'Name', 'Location', 'Status']
            ],
        ];
    }

    /**
     * @test
     * @dataProvider headers
     */
    function it_renders_headers_in_correct_order($headers)
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->assertSeeInOrder($headers);
    }

    /** @test */
    function it_renders_data_in_correct_order()
    {
        $resolver = User::factory()->resolver()->create();
        $user = User::factory([
            'email' => 'average.joe@gmail.com',
            'name' => 'Average Joe',
            'location' => Location::NAMESTOVO,
            'status' => UserStatus::ACTIVE,
        ])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->assertSeeInOrder(
                ['average.joe@gmail.com', 'Average Joe', 'Námestovo', 'Active']
            );
    }

    /** @test */
    function it_renders_users_in_descending_order_by_email_address_property()
    {
        $resolver = User::factory()->resolver()->create();
        User::factory(['email' => '1@gmail.com'])->create();
        User::factory(['email' => '2@gmail.com'])->create();
        User::factory(['email' => '3@gmail.com'])->create();
        User::factory(['email' => '4@gmail.com'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->assertSeeInOrder(['4@gmail.com', '3@gmail.com', '2@gmail.com', '1@gmail.com']);
    }

    /** @test */
    function it_renders_users_in_ascending_order_by_email_address_property_if_wire_click_on_email_property()
    {
        $resolver = User::factory()->resolver()->create();
        User::factory(['email' => '1@gmail.com'])->create();
        User::factory(['email' => '2@gmail.com'])->create();
        User::factory(['email' => '3@gmail.com'])->create();
        User::factory(['email' => '4@gmail.com'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->call('columnHeaderClicked', 'email')
            ->assertSeeInOrder(['1@gmail.com', '2@gmail.com', '3@gmail.com', '4@gmail.com']);
    }

    /** @test */
    function it_renders_users_in_descending_order_by_email_address_property_if_wire_click_on_email_property_twice()
    {
        $resolver = User::factory()->resolver()->create();
        User::factory(['email' => '1@gmail.com'])->create();
        User::factory(['email' => '2@gmail.com'])->create();
        User::factory(['email' => '3@gmail.com'])->create();
        User::factory(['email' => '4@gmail.com'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->call('columnHeaderClicked', 'email')
            ->call('columnHeaderClicked', 'email')
            ->assertSeeInOrder(['4@gmail.com', '3@gmail.com', '2@gmail.com', '1@gmail.com']);
    }

    /** @test */
    function it_renders_users_in_ascending_order_by_name_address_property_if_wire_click_on_name_property()
    {
        $resolver = User::factory()->resolver()->create();
        User::factory(['name' => 'Andre'])->create();
        User::factory(['name' => 'Boris'])->create();
        User::factory(['name' => 'Cecilia'])->create();
        User::factory(['name' => 'Daniel'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->call('columnHeaderClicked', 'name')
            ->assertSeeInOrder(['Andre', 'Boris', 'Cecilia', 'Daniel']);
    }

    /** @test */
    function it_filters_users_based_on_text_input_in_email_column()
    {
        $resolver = User::factory()->resolver()->create();
        User::factory(['email' => '1@gmail.com'])->create();
        User::factory(['email' => '2@gmail.com'])->create();
        User::factory(['email' => '3@gmail.com'])->create();
        User::factory(['email' => '4@gmail.com'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->assertSee('1@gmail.com')
            ->assertSee('2@gmail.com')
            ->assertSee('3@gmail.com')
            ->assertSee('4@gmail.com')
            ->set('searchCases.email', '1')
            ->assertSee('1@gmail.com')
            ->assertDontSee('2@gmail.com')
            ->assertDontSee('3@gmail.com')
            ->assertDontSee('4@gmail.com');
    }

    /** @test */
    function it_renders_users_based_on_table_pagination_input_field()
    {
        $resolver = User::factory()->resolver()->create();
        User::factory(['email' => '1@gmail.com'])->create();
        User::factory(['email' => '2@gmail.com'])->create();
        User::factory(['email' => '3@gmail.com'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->set('paginationIndex', 3)
            ->assertSee('1@gmail.com')
            ->assertDontSee('2@gmail.com')
            ->assertDontSee('3@gmail.com')
            ->set('paginationIndex', 2)
            ->assertSee('1@gmail.com')
            ->assertSee('2@gmail.com')
            ->assertDontSee('3@gmail.com');
    }

    /** @test */
    function it_paginates_25_users_by_default()
    {
        $resolver = User::factory()->resolver()->create();
        // order is descending by default, so the first call created users, which will be rendered on second page
        User::factory(25, ['name' => 'Second page user'])->create();
        User::factory(25, ['name' => 'First page user'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->assertSee('First page user')
            ->assertDontSee('Second page user');
    }

    static function invalidPaginationIndexInput(): array
    {
        return [
            [0],
            [(Table::DEFAULT_ITEMS_PER_PAGE * 2) + 100],
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
        // order is descending by default, so the first call created users, which will be rendered on second page
        User::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['name' => 'Second page user'])->create();
        User::factory(Table::DEFAULT_ITEMS_PER_PAGE, ['name' => 'First page user'])->create();

        Livewire::actingAs($resolver)
            ->test(UsersTable::class)
            ->set('paginationIndex', $invalidInput)
            ->assertSee('First page user')
            ->assertDontSee('Second page user');
    }
}

