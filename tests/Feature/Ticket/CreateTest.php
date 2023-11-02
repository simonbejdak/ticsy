<?php


namespace Tests\Feature\Ticket;

use App\Models\Category;
use App\Models\Item;
use App\Models\TicketConfig;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
            max(TicketConfig::CATEGORIES) + 1 => 'The category field must not be greater than '. max(TicketConfig::CATEGORIES) .'.',
            min(TicketConfig::CATEGORIES) - 1 => 'The category field must be at least 1.',
            'ASAP' => 'The category field must be a number.',
            '' => 'The category field must be a number.',
        ];

        $this->actingAs($user);
        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => TicketConfig::TYPES['incident'],
                'category' => $testedValue,
                'description' => Str::random(TicketConfig::MIN_DESCRIPTION_CHARS),
                'priority' => TicketConfig::DEFAULT_PRIORITY,
            ]));

            $response->assertSessionHasErrors(['category' => $error]);
        }
    }

    function test_it_fails_validation_with_invalid_item(){
        $user = User::factory()->create();
        $item = Item::findOrFail(TicketConfig::ITEMS['issue']);

        $testedValues = [
            max(TicketConfig::ITEMS) + 1 => 'The item field must not be greater than '. max(TicketConfig::ITEMS) .'.',
            min(TicketConfig::ITEMS) - 1 => 'The item field must be at least 1.',
            'one' => 'The item field must be a number.',
            '' => 'The item field must be a number.',
            $item->id => 'The item field must belong to the selected category',
        ];

        $this->actingAs($user);
        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => TicketConfig::TYPES['incident'],
                'category' => TicketConfig::CATEGORIES['network'],
                'item' => $testedValue,
                'description' => Str::random(TicketConfig::MIN_DESCRIPTION_CHARS),
                'priority' => TicketConfig::DEFAULT_PRIORITY,
            ]));

            $response->assertSessionHasErrors(['item' => $error]);
        }
    }

    function test_it_fails_validation_with_invalid_description(){
        $user = User::factory()->create();

        $testedValues = [
            '' => 'The description field is blank.',
            Str::random(TicketConfig::MIN_DESCRIPTION_CHARS - 1) => 'The description field must be at least '. TicketConfig::MIN_DESCRIPTION_CHARS .' characters.',
            Str::random(TicketConfig::MAX_DESCRIPTION_CHARS + 1) => 'The description field must not be greater than '. TicketConfig::MAX_DESCRIPTION_CHARS .' characters.',
        ];

        $this->actingAs($user);
        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => TicketConfig::TYPES['incident'],
                'category' => TicketConfig::CATEGORIES['network'],
                'description' => $testedValue,
                'priority' => TicketConfig::DEFAULT_PRIORITY,
            ]));

            $response->assertSessionHasErrors(['description' => $error]);
        }
    }
}
