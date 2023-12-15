<?php

namespace App\View\Components;

use App\Helpers\App;
use App\Interfaces\Fieldable;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Field extends Component
{
    public Fieldable|null $representedModel;
    public string $name;
    public string $displayName;
    public string|array|Collection $value;
    public bool $hideable;
    public bool|string $hasPermission;
    public bool $modifiable;
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
                                $modifiable = null,
                                $percentage = null,
                                $blank = false,
                                $displayName = null,
    ){
        $this->name = $name;
        $this->value = is_string($value) ? $value : App::toIterable($value);
        $this->displayName = $displayName ?? App::makeDisplayName($name);
        $this->representedModel = $representedModel;
        $this->hasPermission = $hasPermission;
//        $this->disabled = $disabled ?? $this->isDisabled();
        $this->modifiable = $modifiable ?? $representedModel->isFieldModifiable($this->name);
        $this->disabled = !$this->modifiable;
        $this->percentage = $percentage;
        $this->blank = $blank;
        $this->hideable = $hideable;
        $this->hidden = $this->hideable && !$this->modifiable;
        $this->type = $this->setType();
    }

    public function render(): View|Closure|string
    {
        return view('components.field');
    }

//    protected function isDisabled(): bool
//    {
//        if(!$this->hasPermission){
//            return false;
//        }
//        if(is_string($this->hasPermission)){
//            return !auth()->user()->hasPermissionTo($this->hasPermission);
//        }
//        if($this->fieldableModel === null){
//            return !auth()->user()->hasPermissionTo('set_' . $this->name);
//        }
//
//        return auth()->user()->cannot('set' . ucfirst($this->name), $this->fieldableModel);
//    }

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
