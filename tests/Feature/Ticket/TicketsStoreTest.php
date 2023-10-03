<?php


namespace Tests\Feature\Ticket;

use App\Models\Incident;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class TicketsStoreTest extends TestCase
{
    use RefreshDatabase;
    function test_it_permits_authenticated_user_to_store_ticket(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $tested = [
            'description' => Str::random(Ticket::MINIMUM_DESCRIPTION_CHARACTERS + 1),
        ];

        $response = $this->post(route('tickets.store', [
            'type' => Ticket::TYPES['incident'],
            'category' => Ticket::CATEGORIES['network'],
            'description' => $tested['description'],
            'priority' => Ticket::DEFAULT_PRIORITY,
        ]));

        $response->assertRedirectToRoute('tickets.show', 1);

        $this->assertEquals($tested['description'], $user->tickets()->find(1)->description);
    }

    function test_it_fails_validation_with_invalid_category(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $testedValues = [
            max(Ticket::CATEGORIES) + 1 => 'The category field must not be greater than 5.',
            min(Ticket::CATEGORIES) - 1 => 'The category field must be at least 1.',
            'ASAP' => 'The category field must be a number.',
            '' => 'The category field must be a number.',
        ];

        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => Ticket::TYPES['incident'],
                'category' => $testedValue,
                'description' => Str::random(Ticket::MINIMUM_DESCRIPTION_CHARACTERS),
                'priority' => Ticket::DEFAULT_PRIORITY,
            ]));

            $response->assertSessionHasErrors(['category' => $error]);
        }
    }
    function test_it_fails_validation_with_invalid_description(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $testedValues = [
            '' => 'The description field is required.',
            Str::random(Ticket::MINIMUM_DESCRIPTION_CHARACTERS - 1) => 'The description field must be at least 8 characters.',
            Str::random(Ticket::MAXIMUM_DESCRIPTION_CHARACTERS + 1) => 'The description field must not be greater than 255 characters.',
        ];

        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => Ticket::TYPES['incident'],
                'category' => Ticket::CATEGORIES['network'],
                'description' => $testedValue,
                'priority' => Ticket::DEFAULT_PRIORITY,
            ]));

            $response->assertSessionHasErrors(['description' => $error]);
        }
    }

    function test_it_fails_validation_with_invalid_priority(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $testedValues = [
            max(Ticket::PRIORITIES) + 1 => 'The priority field must not be greater than 4.',
            min(Ticket::PRIORITIES) - 1 => 'The priority field must be at least 1.',
            'ASAP' => 'The priority field must be a number.',
            '' => 'The priority field must be a number.',
        ];

        foreach ($testedValues as $testedValue => $error){
            $response = $this->post(route('tickets.store', [
                'type' => Ticket::TYPES['incident'],
                'category' => Ticket::CATEGORIES['network'],
                'description' => Str::random(Ticket::MINIMUM_DESCRIPTION_CHARACTERS),
                'priority' => $testedValue,
            ]));

            $response->assertSessionHasErrors(['priority' => $error]);
        }
    }
}
