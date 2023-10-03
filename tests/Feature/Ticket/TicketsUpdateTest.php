<?php


namespace Tests\Feature\Ticket;

use App\Models\Incident;
use App\Models\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class TicketsUpdateTest extends TestCase
{
    use RefreshDatabase;

    function test_it_errors_to_403_to_unauthorized_users()
    {
        Ticket::factory()->create();
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('tickets.update', 1));

        $response->assertForbidden();
    }

    function test_it_permits_auth_user_to_update_his_ticket(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory(['user_id' => $user, 'type_id' => Ticket::TYPES['incident']])->create();

        $response = $this->patch(route('tickets.update', $ticket), [
            'priority' => Ticket::DEFAULT_PRIORITY - 1,
        ]);

        $response->assertRedirectToRoute('tickets.show', 1);

        $this->assertEquals(Ticket::DEFAULT_PRIORITY - 1, $user->tickets()->find(1)->priority);
    }

    function test_it_fails_validation_with_invalid_priority(){
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user, 'type_id' => Ticket::TYPES['incident']])->create();

        $this->actingAs($user);

        $testedValues = [
            max(Ticket::PRIORITIES) + 1 => 'The priority field must not be greater than 4.',
            min(Ticket::PRIORITIES) - 1 => 'The priority field must be at least 1.',
            'ASAP' => 'The priority field must be a number.',
            '' => 'The priority field must be a number.',
        ];

        foreach ($testedValues as $testedValue => $error){
            $response = $this->patch(route('tickets.update', $ticket), [
                'priority' => $testedValue,
            ]);

            $response->assertSessionHasErrors(['priority' => $error]);
            $this->assertEquals(4, $ticket->priority);
        }
    }
}
