<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketActivities extends Component
{
    public array $activities;
    public Ticket $ticket;
    public string $body = '';
    protected $listeners = ['ticket-updated'];

    public function render()
    {
        $this->activities = $this->setActivities();

        return view('livewire.ticket-activities');
    }

    public function addComment(){
        $this->authorize('addComment', $this->ticket);

        $this->validate([
            'body' => 'min:'. Comment::MIN_BODY_CHARS .'|max:'. Comment::MAX_BODY_CHARS .'|required',
        ]);

        $this->ticket->addComment($this->body);

        $this->reset('body');
    }

    #[On('ticket-updated')]
    public function ticketUpdated()
    {
        $this->render();
    }

    public function setActivities(): array
    {
        $return = [];

        foreach ($this->ticket->activities()->orderByDesc('created_at')->get() as $activity){
            $modifiedActivity = [];
            $modifiedActivity['user'] = $activity->causer;
            $modifiedActivity['created_at'] = $activity->created_at;
            $modifiedActivity['border_color'] = ($activity->event === 'comment') ? 'border-black' : 'border-gray-300';
            $modifiedActivity['event'] = $activity->event;

            if($activity->event === 'comment') {
                $modifiedActivity['body'] = $activity->description;
            } else {
                foreach ($activity->changes['attributes'] as $change => $values){
                    // preg - put space in front of capital letters
                    $modifiedActivity['rows'][$change]['field_name'] = ucfirst(strtolower(preg_replace('/(?<!\ )[A-Z]/', ' $0', $change)));
                    if(is_array($values)){
                        $modifiedActivity['rows'][$change]['new_value'] = $values['name'];
                    } else {
                        // values is in this case a simple attribute, not an array
                        $modifiedActivity['rows'][$change]['new_value'] = $values;
                    }
                }

                if(isset($activity->changes['old'])){
                    foreach ($activity->changes['old'] as $change => $values){
                        if(is_array($values)){
                            $modifiedActivity['rows'][$change]['old_value'] = $values['name'];
                        } else {
                            // values is in this case a simple attribute, not an array
                            $modifiedActivity['rows'][$change]['old_value'] = $values;
                        }
                    }
                }
            }

            $return[] = $modifiedActivity;
        }

        return $return;
    }
}
