<?php

namespace App\Helpers;

use App\Enums\FieldPosition;
use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Collection;

class Field
{
    public string $name;
    public string $displayName;
    public string|array|null $value;
    public bool $modifiable;
    public bool $hideable;
    public bool $blank;
    public int $percentage;
    public FieldType $type;
    public FieldPosition $position;

    protected function __construct(){}

    static function text(
        string $name,
        FieldPosition $position = null,
        string $displayName = null,
        string $value = null,
        bool $hideable = false,
        bool $modifiable = false,
    ): self
    {
        $self = new self();
        $self->name = $name;
        $self->displayName = $displayName ?? makeDisplayName($name);
        $self->value = $value;
        $self->position = $position ?? FieldPosition::IN_GRID;
        $self->type = FieldType::TEXT;
        $self->modifiable = $modifiable;
        $self->hideable = $hideable;

        return $self;
    }

    static function select(
        string $name,
        FieldPosition $position = null,
        string $displayName = null,
        array|Collection $options = null,
        bool $hideable = false,
        bool $blank = false,
        bool $modifiable = false,
    ): self
    {
        $self = new self();
        $self->name = $name;
        $self->displayName = $displayName ?? makeDisplayName($name);
        $self->value = toIterable($options);
        $self->position = $position ?? FieldPosition::IN_GRID;
        $self->type = FieldType::SELECT;
        $self->modifiable = $modifiable;
        $self->hideable = $hideable;
        $self->blank = $blank;

        return $self;
    }

    static function bar(
        string $name,
        int $percentage,
        FieldPosition $position = null,
        string $displayName = null,
        string $value = null,
    ): self
    {
        $self = new self();
        $self->name = $name;
        $self->displayName = $displayName ?? makeDisplayName($name);
        $self->value = $value;
        $self->percentage = $percentage;
        $self->position = $position ?? FieldPosition::IN_GRID;
        $self->type = FieldType::BAR;

        return $self;
    }

}
