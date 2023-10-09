<?php


namespace Tests\Feature\Ticket;

use App\Models\Incident;
use App\Models\Ticket;
use App\Models\TicketConfiguration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    function test_it_permits_authenticated_user_to_store_ticket(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $tested = [
            'description' => Str::random(TicketConfiguration::MINIMUM_DESCRIPTION_CHARACTERS + 1),
        ];

        $response = $this->post(route('tickets.store', [
            'type' => TicketConfiguration::TYPES['incident'],
            'category' => TicketConfiguration::CATEGORIES['network'],
            'description' => $tested['description'],
            'priority' => TicketConfiguration::DEFAULT_PRIORITY,
        ]));

        $response->assertRedirectToRoute('tickets.edit', 1);

        $this->assertEquals($tested['description'], $user->tickets()->find(1)->description);
    }

    function test_it_fails_validation_with_invalid_category(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $testedValues = [
            max(TicketConfiguration::CATEGORIES) + 1 => 'The category field must not be greater than 5.',
            min(TicketConfiguration::CATEGORIES) - 1 => 'The category field must be at least 1.',
            'ASAP' => 'The category field must be a number.',
            '' => 'The category field must be a number.',
        ];

        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => TicketConfiguration::TYPES['incident'],
                'category' => $testedValue,
                'description' => Str::random(TicketConfiguration::MINIMUM_DESCRIPTION_CHARACTERS),
                'priority' => TicketConfiguration::DEFAULT_PRIORITY,
            ]));

            $response->assertSessionHasErrors(['category' => $error]);
        }
    }
    function test_it_fails_validation_with_invalid_description(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $testedValues = [
            '' => 'The description field is required.',
            Str::random(TicketConfiguration::MINIMUM_DESCRIPTION_CHARACTERS - 1) => 'The description field must be at least '. TicketConfiguration::MINIMUM_DESCRIPTION_CHARACTERS .' characters.',
            Str::random(TicketConfiguration::MAXIMUM_DESCRIPTION_CHARACTERS + 1) => 'The description field must not be greater than '. TicketConfiguration::MAXIMUM_DESCRIPTION_CHARACTERS .' characters.',
        ];

        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => TicketConfiguration::TYPES['incident'],
                'category' => TicketConfiguration::CATEGORIES['network'],
                'description' => $testedValue,
                'priority' => TicketConfiguration::DEFAULT_PRIORITY,
            ]));

            $response->assertSessionHasErrors(['description' => $error]);
        }
    }
}
