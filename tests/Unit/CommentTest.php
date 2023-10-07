<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_ticket_relationship()
    {
        $ticket = Ticket::factory(['description' => 'Ticket Description'])->create();
        $comment = Comment::factory(['ticket_id' => $ticket])->create();

        $this->assertEquals('Ticket Description', $comment->ticket->description);
    }

    public function test_it_has_belongs_to_user_relationship()
    {
        $user = User::factory(['name' => 'John Doe'])->create();
        $comment = Comment::factory(['user_id' => $user])->create();

        $this->assertEquals('John Doe', $comment->user->name);
    }

    public function test_it_has_body()
    {
        $comment = Comment::factory(['body' => 'Comment Body'])->create();

        $this->assertEquals('Comment Body', $comment->body);
    }
}
