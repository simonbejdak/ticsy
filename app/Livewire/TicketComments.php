<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TicketComments extends Component
{
    public Collection $comments;
    public Ticket $ticket;
    public string $body = '';

    public function render()
    {
        $this->comments = $this->ticket->comments()->orderBy('created_at', 'DESC')->get();

        return view('livewire.ticket-comments');
    }

    public function addComment(){
        $this->authorize('addComment', $this->ticket);

        $this->validate([
            'body' => 'min:'. Comment::MIN_BODY_CHARS .'|max:'. Comment::MAX_BODY_CHARS .'|required',
        ]);

        $comment = new Comment();
        $comment->ticket_id = $this->ticket->id;
        $comment->user_id = Auth::user()->id;
        $comment->body = $this->body;
        $comment->save();

        $this->reset('body');
    }
}
