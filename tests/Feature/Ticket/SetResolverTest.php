<?php

namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;

class SetResolverTest extends TestCase
{
    public function test_guest_is_redirected_to_login_page()
    {
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->patch(route('tickets.set-resolver', $ticket), [
            'resolver' => $resolver
        ]);

        $response->assertRedirectToRoute('login');
    }

    public function test_non_resolver_user_cannot_set_resolver()
    {
        $user = User::factory()->create();
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.set-resolver', $ticket), [
            'resolver' => $resolver
        ]);

        $response->assertForbidden();
    }

    public function test_resolver_user_can_set_resolver()
    {
        $user = User::factory()->resolver()->create();
        $resolver = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.set-resolver', $ticket), [
            'resolver' => $resolver->id
        ]);

        $response->assertRedirectToRoute('tickets.edit', $ticket);
    }
}
