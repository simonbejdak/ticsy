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
    public $value;
    public bool $hideable;
    public bool|string $permission;
    public bool $disabled;
    public $error;
    public bool $hidden;

    public function __construct(
        string $name,
        Model|null $representedModel = null,
        bool $hideable = false,
        bool|string $permission = true,
        $value = '',
        bool $disabled = null,
        string $displayName = null,
    ){
        $this->name = $name;
        $this->value = $value;
        $this->displayName = $displayName !== null ? $displayName : App::makeDisplayName($name);
        $this->representedModel = $representedModel;
        $this->permission = $permission;
        $this->disabled = $disabled !== null ? $disabled : $this->isDisabled();
        $this->hideable = $hideable;
        $this->hidden = $this->hideable && $this->disabled;
    }

    public function render(): View|Closure|string
    {
        return view('components.field');
    }

    protected function isDisabled(): bool
    {
        if(!$this->permission){
            return false;
        }
        if(is_string($this->permission)){
            return !auth()->user()->hasPermissionTo($this->permission);
        }
        if($this->representedModel === null){
            return !auth()->user()->hasPermissionTo('set_' . $this->name);
        }

        return auth()->user()->cannot('set' . ucfirst($this->name), $this->representedModel);
    }
}
