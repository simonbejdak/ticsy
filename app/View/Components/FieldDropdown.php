<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FieldDropdown extends Field
{
    public Collection|array $options;
    public bool $blank;

    public function __construct(
        string $name,
        Collection|array $options,
        Model|null $representedModel = null,
        bool $hideable = false,
        bool $blank = false,
    )
    {
        parent::__construct($name, $representedModel, $hideable);
        $this->options = $this->toIterable($options);
        $this->blank = $blank;
    }

    public function render(): View|Closure|string
    {
        return view('components.field-dropdown');
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
