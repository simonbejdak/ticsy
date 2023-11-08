<?php

namespace App\Helpers;

class App
{
    public static function addSpacesBeforeUppercase($value): string {
        return preg_replace('/([A-Z])/', ' $1', $value);
    }

    public static function makeDisplayName($name): string{
        $name = self::addSpacesBeforeUppercase($name);
        $name = strtolower($name);

        return ucfirst($name);
    }
}
