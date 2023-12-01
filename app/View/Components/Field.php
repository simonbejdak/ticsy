<?php

namespace App\View\Components;

use App\Helpers\App;
use App\Models\Ticket;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;
use InvalidArgumentException;

class Field extends Component
{
    public Model|null $representedModel;
    public string $name;
    public string $displayName;
    public string|array|Collection $value;
    public bool $hideable;
    public bool|string $hasPermission;
    public bool $disabled;
    public bool $blank;
    public int|null $percentage;
    public $error;
    public bool $hidden;
    public string $type;

    public function __construct($name,
                                $representedModel = null,
                                $hideable = false,
                                $hasPermission = true,
                                string|array|Collection $value = '',
                                $disabled = null,
                                $percentage = null,
                                $blank = false,
                                $displayName = null,
    ){
        $this->name = $name;
        $this->value = is_string($value) ? $value : $this->toIterable($value);
        $this->displayName = $displayName !== null ? $displayName : App::makeDisplayName($name);
        $this->representedModel = $representedModel;
        $this->hasPermission = $hasPermission;
        $this->disabled = $disabled !== null ? $disabled : $this->isDisabled();
        $this->percentage = $percentage;
        $this->blank = $blank;
        $this->hideable = $hideable;
        $this->hidden = $this->hideable && $this->disabled;
        $this->type = $this->setType();
    }

    public function render(): View|Closure|string
    {
        return view('components.field');
    }

    protected function isDisabled(): bool
    {
        if(!$this->hasPermission){
            return false;
        }
        if(is_string($this->hasPermission)){
            return !auth()->user()->hasPermissionTo($this->hasPermission);
        }
        if($this->representedModel === null){
            return !auth()->user()->hasPermissionTo('set_' . $this->name);
        }

        return auth()->user()->cannot('set' . ucfirst($this->name), $this->representedModel);
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

    protected function setType(): string{
        if(is_string($this->value)){
            if($this->percentage !== null){
                $type = 'bar';
            } else {
                $type = 'input';
            }
        } else {
            $type = 'select';
        }

        return $type;
    }
}
