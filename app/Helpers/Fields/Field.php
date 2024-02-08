<?php

namespace App\Helpers\Fields;

use App\Enums\FieldLabelPosition;
use App\Enums\FieldPosition;
use App\Interfaces\Fieldable;

abstract class Field
{
    public string $name;
    public bool $hasLabel;
    public string $value;
    public string $rules;
    public string $width;
    public string $height;
    public string $wireModel;
    protected string $label;
    public bool $hideable;
    protected bool $disabled;
    public bool $hidden;
    public bool $error;
    public FieldPosition $position;
    public FieldLabelPosition $labelPosition;

    protected function __construct(){}

    static function make(string $name): static
    {
        $static = new static;
        $static->name = $name;
        $static->value = '';
        $static->width = 'w-3/5';
        $static->height = 'h-6';
        $static->wireModel = $static->name;
        $static->hasLabel = true;
        $static->hideable = false;
        $static->disabled = false;
        $static->hidden = false;
        $static->error = session('error') ? session('error')->first($static->name) : false;
        $static->position = FieldPosition::INSIDE_GRID;
        $static->labelPosition = FieldLabelPosition::LEFT;

        return $static;
    }

    function value(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    function getLabel(): string
    {
        return $this->label ?? makeDisplayName($this->name);
    }

    function hideable(): self
    {
        $this->hideable = true;
        return $this;
    }

    function disabled(): self
    {
        $this->disabled = true;
        return $this;
    }

    function disabledIf(bool $condition): self
    {
        $this->disabled = $condition;
        return $this;
    }

    function isHidden(): bool
    {
        return $this->hidden;
    }

    function required(): self
    {
        $this->rules .= '|required';
        return $this;
    }

    function numeric(): self
    {
        $this->rules .= '|numeric';
        return $this;
    }

    function requiredIf(bool $condition): self
    {
        if($condition){
            $this->required();
        }
        return $this;
    }

    function nullable(): self
    {
        $this->rules .= '|nullable';
        return $this;
    }

    function string(): self
    {
        $this->rules .= '|string';
        return $this;
    }

    function present(): self
    {
        $this->rules .= '|present';
        return $this;
    }

    function max(int $value): self
    {
        $this->rules .= '|max:' . $value;
        return $this;
    }

    function outsideGrid(): self
    {
        $this->position = FieldPosition::OUTSIDE_GRID;
        $this->width = 'w-4/5';
        return $this;
    }

    function withoutLabel(): self
    {
        $this->hasLabel = false;
        return $this;
    }

    function hiddenIf(bool $condition): self
    {
        $this->hidden = $condition;
        return $this;
    }

    function isDisabled(): bool
    {
        return $this->disabled;
    }

    function wireModel(string $wireModel): self
    {
        $this->wireModel = $wireModel;
        return $this;
    }

    function labelPosition(FieldLabelPosition $labelPosition): self
    {
        $this->labelPosition = $labelPosition;
        return $this;
    }

    function style(): string
    {
        return
            ($this->disabled ? 'text-gray-800 bg-slate-100 cursor-not-allowed ' : 'bg-white ') .
            ($this->error ? 'ring-1 ring-red-500 ' : '') .
            $this->height .
            ' appearance-none px-2 rounded-sm shadow-inner text-xs border border-slate-400 text-black caret-inherit focus:border-indigo-500 focus:ring-indigo-500 ';
    }
}
