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
    public $body;

    public function render()
    {
        $this->comments = $this->ticket->comments()->orderBy('created_at', 'DESC')->get();

        return view('livewire.ticket-comments');
    }

    public function addComment(Request $request){
        $this->authorize('addComment', $this->ticket);

        $this->validate([
            'body' => 'min:'. Comment::MINIMAL_BODY_CHARACTERS .'|max:'. Comment::MAXIMAL_BODY_CHARACTERS .'|required',
        ]);

        $comment = new Comment();
        $comment->ticket_id = $this->ticket->id;
        $comment->user_id = Auth::user()->id;
        $comment->body = $this->body;
        $comment->save();

        $this->body = '';
        $this->render();
    }
}
