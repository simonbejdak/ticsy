<?php


namespace Tests\Feature\Ticket;

use App\Livewire\TicketCreateForm;
use App\Models\Category;
use App\Models\Item;
use App\Models\TicketConfig;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    function test_it_redirects_guests_to_login_page()
    {
        $response = $this->get(route('tickets.create'));

        $response->assertRedirectToRoute('login');
    }

    function test_it_loads_to_auth_users()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('tickets.create'));

        $response->assertSuccessful();
        $response->assertSee('Create Incident');
    }

    function test_it_fails_validation_with_invalid_category(){
        $user = User::factory()->create();

        $testedValues = [
            max(TicketConfig::CATEGORIES) + 1 => 'max',
            min(TicketConfig::CATEGORIES) - 1 => 'min',
            'ASAP' => 'numeric',
            '' => 'required',
        ];

        Livewire::actingAs($user);

        foreach ($testedValues as $testedValue => $error){
            Livewire::test(TicketCreateForm::class, ['type' => Type::first()])
                ->set('category', $testedValue)
                ->call('create')
                ->assertHasErrors(['category' => $error]);
        }
    }

    function test_it_fails_validation_with_invalid_item(){
        $user = User::factory()->create();

        $testedValues = [
            '' => 'required',
            max(TicketConfig::ITEMS) + 1 => 'max',
            min(TicketConfig::ITEMS) - 1 => 'min',
        ];

        Livewire::actingAs($user);

        foreach ($testedValues as $testedValue => $error){
            Livewire::test(TicketCreateForm::class, ['type' => Type::first()])
                ->set('item', $testedValue)
                ->call('create')
                ->assertHasErrors(['item' => $error]);
        }
    }

    function test_it_fails_validation_with_invalid_description(){
        $user = User::factory()->create();

        $testedValues = [
            '' => 'required',
            Str::random(TicketConfig::MIN_DESCRIPTION_CHARS - 1) => 'min',
            Str::random(TicketConfig::MAX_DESCRIPTION_CHARS + 1) => 'max',
        ];

        Livewire::actingAs($user);

        foreach ($testedValues as $testedValue => $error){
            Livewire::test(TicketCreateForm::class, ['type' => Type::first()])
                ->set('description', $testedValue)
                ->call('create')
                ->assertHasErrors(['description' => $error]);
        }
    }

    function test_user_can_set_category(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(TicketCreateForm::class)
            ->set('category', TicketConfig::CATEGORIES['email'])
            ->call('create')
            ->assertHasNoErrors(['category' => 'required']);
    }

    function test_user_can_set_item(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(TicketCreateForm::class)
            ->set('item', TicketConfig::ITEMS['issue'])
            ->call('create')
            ->assertHasNoErrors(['item' => 'required']);
    }

    function test_user_can_set_description(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(TicketCreateForm::class)
            ->set('description', Str::random(TicketConfig::MIN_DESCRIPTION_CHARS + 1))
            ->call('create')
            ->assertHasNoErrors(['description' => 'required']);
    }
}
