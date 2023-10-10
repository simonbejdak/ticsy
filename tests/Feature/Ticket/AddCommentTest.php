<?php

namespace Tests\Feature\Ticket;

use App\Livewire\TicketComments;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Str;
use Tests\TestCase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_throws_403_to_user_who_has_not_created_the_ticket()
    {
        $ticket = Ticket::factory()->create();
        $user = User::factory()->create();

        Livewire::actingAs($user);

        Livewire::test(TicketComments::class, ['ticket' => $ticket])
            ->call('addComment', ['body' => 'Comment Body',])
            ->assertForbidden();
    }

    public function test_it_allows_to_add_comment_to_user_who_has_created_the_ticket()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        Livewire::actingAs($user);

        Livewire::test(TicketComments::class, ['ticket' => $ticket])
            ->set('body', 'Comment Body')
            ->call('addComment')
            ->assertSee('Comment Body');

        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Comment Body'
        ]);
    }

    public function test_it_allows_to_add_comment_to_resolver()
    {
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TicketComments::class, ['ticket' => $ticket])
            ->set('body', 'Comment Body')
            ->call('addComment')
            ->assertSee('Comment Body');

        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $resolver->id,
            'body' => 'Comment Body'
        ]);
    }

    public function test_it_fails_validation_with_empty_body()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        Livewire::actingAs($user);

        Livewire::test(TicketComments::class, ['ticket' => $ticket])
            ->set('body', '')
            ->call('addComment')
            ->assertHasErrors(['body' => 'required']);
    }

    public function test_it_fails_validation_with_body_having_less_characters_than_predefined()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        Livewire::actingAs($user);

        Livewire::test(TicketComments::class, ['ticket' => $ticket])
            ->set('body', Str::random(Comment::MINIMAL_BODY_CHARACTERS - 1))
            ->call('addComment')
            ->assertHasErrors(['body' => 'min']);
    }

    public function test_it_fails_validation_with_body_having_more_characters_than_predefined()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        Livewire::actingAs($user);

        Livewire::test(TicketComments::class, ['ticket' => $ticket])
            ->set('body', Str::random(Comment::MAXIMAL_BODY_CHARACTERS + 1))
            ->call('addComment')
            ->assertHasErrors(['body' => 'max']);
    }
}
