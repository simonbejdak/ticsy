<?php

namespace App\View\Components;

use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use InvalidArgumentException;

class TicketFieldSelect extends TicketField
{
    public Collection|array $options;
    public bool $blank;

    public function __construct(
        string $name,
        Collection|array $options,
        Ticket|null $ticket = null,
        bool $hideable = false,
        bool $blank = false,
    )
    {
        parent::__construct($name, $ticket, $hideable);
        $this->options = $this->toIterable($options);
        $this->blank = $blank;
    }

    public function render(): View|Closure|string
    {
        return view('components.ticket-field-select');
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
