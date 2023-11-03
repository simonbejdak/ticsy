<?php

namespace App\View\Components;

use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;

class TicketFieldSelect extends Component
{
    public Ticket|null $ticket;
    public string $name;
    public Collection|array $options;
    public bool $blank = false;
    public bool $disabled = false;

    public function __construct(string $name, Collection|array $options, Ticket|null $ticket = null,   bool $blank = false)
    {
        $this->name = $name;
        $this->options = $this->toIterable($options);
        $this->ticket = $ticket;
        $this->blank = $blank;
        $this->disabled = $this->isDisabled();
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-select');
    }

    protected function isDisabled(): bool
    {
        if(auth()->user()->cannot('set' . ucfirst($this->name), $this->ticket)){
            return true;
        }
        if($this->ticket->isResolved()){
            return true;
        }
        if($this->ticket->isArchived()){
            return true;
        };

        return false;
    }

    protected function toIterable(Collection|array $object): array{
        $return = [];

        if($object instanceof Collection){
            foreach ($object->all() as $value){
                $return[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                ];
            }
            return $return;
        }
        if(is_array($object)){
            if(array_is_list($object)){
                foreach ($object as $value){
                    $return[] = ['id' => $value, 'name' => $value];
                }
            }
            else {
                foreach ($object as $key => $value){
                    $return[] = ['id' => $value, 'name' => $key];
                }
            }
            return $return;
        }

        throw new InvalidArgumentException('Method toIterable() only accepts arguments of type Collection or array');
    }
}
