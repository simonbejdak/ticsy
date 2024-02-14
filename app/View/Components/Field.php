<?php

namespace App\View\Components;

use App\Helpers\Fields\Bar;
use App\Helpers\Fields\CheckBox;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use InvalidArgumentException;

class Field extends Component
{
    public \App\Helpers\Fields\Field $field;

    public function __construct(\App\Helpers\Fields\Field $field){
        $this->field = $field;
    }

    public function render(): View|Closure|string
    {
        if($this->field instanceof TextInput){
            return view('components.text-input');
        } elseif($this->field instanceof TextArea){
            return view('components.text-area');
        } elseif($this->field instanceof Select){
            return view('components.select');
        } elseif($this->field instanceof Bar){
            return view('components.bar');
        } elseif($this->field instanceof CheckBox){
            return view('components.check-box');
        }

        throw new InvalidArgumentException('Field ' . get_class($this->field) . ' cannot be rendered. ');
    }

}
