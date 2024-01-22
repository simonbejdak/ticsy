<?php

namespace App\Helpers\Fields;

use App\Enums\FieldPosition;
use App\Interfaces\Fieldable;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Collection;

abstract class Field
{
    public string $name;
    public bool $hasLabel;
    public string $value;
    public string $rules;
    protected string $displayName;
    public bool $hideable;
    protected bool $disabled;
    public bool $hidden;
    public FieldPosition $position;

    protected function __construct()
    {
        $this->value = '';
        $this->hasLabel = true;
        $this->hideable = false;
        $this->disabled = false;
        $this->hidden = false;
        $this->position = FieldPosition::INSIDE_GRID;
    }

    static function make(string $name): static
    {
        $static = new static;
        $static->name = $name;

        return $static;
    }

    function value(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    function displayName(string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    function getDisplayName(): string
    {
        return $this->displayName ?? makeDisplayName($this->name);
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
}
