<?php


namespace Tests\Feature\Ticket;

use App\Models\Incident;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    function test_it_permits_authenticated_user_to_store_ticket(){
        $user = User::factory()->create();
        $description = Str::random(TicketConfig::MIN_DESCRIPTION_CHARS + 1);

        $this->actingAs($user);
        $response = $this->post(route('tickets.store', [
            'type' => TicketConfig::TYPES['incident'],
            'category' => TicketConfig::CATEGORIES['network'],
            'description' => $description,
            'priority' => TicketConfig::DEFAULT_PRIORITY,
        ]));

        $response->assertRedirectToRoute('tickets.edit', 1);
        $this->assertEquals($description, $user->tickets()->find(1)->description);
    }

    function test_it_fails_validation_with_invalid_category(){
        $user = User::factory()->create();

        $testedValues = [
            max(TicketConfig::CATEGORIES) + 1 => 'The category field must not be greater than 5.',
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
    function test_it_fails_validation_with_invalid_description(){
        $user = User::factory()->create();

        $testedValues = [
            '' => 'The description field is required.',
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
