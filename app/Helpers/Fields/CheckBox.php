<?php

namespace App\Helpers\Fields;

class CheckBox extends Field
{
    protected bool $checked;

    static function make(string $name): static
    {
        $static = parent::make($name);
        $static->checked = false;

        return $static;
    }

    function checkedIf(bool $condition): self
    {
        if($condition){
            $this->checked = true;
        }

        return $this;
    }

    function isChecked(): bool
    {
        return $this->checked;
    }

    function style(): string
    {
        return
            $this->height .
            ' w-6 relative peer block disabled:opacity-100 disabled:bg-slate-100 appearance-none disabled:cursor-not-allowed hover:cursor-pointer px-2 rounded-sm shadow-inner text-xs border border-slate-400 caret-inherit ';
    }
}
