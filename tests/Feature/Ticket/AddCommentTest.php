<?php

namespace Tests\Feature\Ticket;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_redirects_guest_to_login()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->patch(route('tickets.add-comment', $ticket));

        $response->assertRedirectToRoute('login');
    }

    public function test_it_throws_403_to_user_who_has_not_created_the_ticket()
    {
        $ticket = Ticket::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.add-comment', $ticket));
        $response->assertForbidden();
    }

    public function test_it_allows_to_add_comment_to_user_who_has_created_the_ticket()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.add-comment', $ticket), [
            'body' => 'Comment Body',
        ]);

        $response->assertRedirectToRoute('tickets.edit', $ticket);
        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Comment Body'
        ]);
    }

    public function test_it_allows_to_add_comment_to_resolver()
    {
        $user = User::factory()->resolver()->create();
        $ticket = Ticket::factory()->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.add-comment', $ticket), [
            'body' => 'Comment Body',
        ]);

        $response->assertRedirectToRoute('tickets.edit', $ticket);
        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Comment Body'
        ]);
    }

    public function test_it_fails_validation_with_empty_body()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.add-comment', $ticket), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['body' => 'The body field is required.']);
    }

    public function test_it_fails_validation_with_body_having_less_characters_than_predefined()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.add-comment', $ticket), [
            'body' => Str::random(Comment::MINIMAL_BODY_CHARACTERS - 1),
        ]);

        $response->assertSessionHasErrors(['body' => 'The body field must be at least '. Comment::MINIMAL_BODY_CHARACTERS .' characters.']);
    }

    public function test_it_fails_validation_with_body_having_more_characters_than_predefined()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->patch(route('tickets.add-comment', $ticket), [
            'body' => Str::random(Comment::MAXIMAL_BODY_CHARACTERS + 1),
        ]);

        $response->assertSessionHasErrors(['body' => 'The body field must not be greater than '. Comment::MAXIMAL_BODY_CHARACTERS .' characters.']);
    }
}
