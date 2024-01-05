<?php

namespace App\Helpers\Fields;

use App\Interfaces\Fieldable;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;

abstract class Field
{
    public string $name;
    public string $value = '';
    public string $rules;
    protected string $displayName;
    public bool $hideable = false;
    public bool $disabled = false;

    static function make(): static
    {
        return new static;
    }

    function name(string $name): self
    {
        $this->name = $name;
        return $this;
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

    function disabledCondition(bool $condition): self
    {
        $this->disabled = $condition;
        return $this;
    }

    function isHidden(): bool
    {
        return $this->hideable && $this->disabled;
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

}
