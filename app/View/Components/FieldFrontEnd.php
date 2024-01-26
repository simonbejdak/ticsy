<?php

namespace App\View\Components;

use App\Enums\FieldType;
use App\Helpers\Field;
use App\Interfaces\Fieldable;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class FieldFrontEnd extends Component
{
    public string $name;
    public string $displayName;
    public string|array|Collection $value;
    public bool $hideable;
    public bool $modifiable;
    public bool $disabled;
    public bool $blank;
    public int|null $percentage;
    public FieldType $type;
    public bool $hidden;

    public function __construct(Field $field){
        $this->name = $field->name;
        $this->displayName = $field->displayName;
        $this->value = $field->value;
        $this->hideable = $field->hideable;
        $this->modifiable = $field->modifiable;
        $this->disabled = !$this->modifiable;
        $this->blank = $field->blank;
        $this->type = $field->type;
        $this->hidden = $this->hideable && !$this->modifiable;
    }

    public function render(): View|Closure|string
    {
        return view('components.field-front-end');
    }
}
