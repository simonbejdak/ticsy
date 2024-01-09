<?php

namespace App\Enums;

use Illuminate\Testing\Exceptions\InvalidArgumentException;

enum Tab: string
{
    case ACTIVITIES = 'activities';
    case TASKS = 'tasks';

    static function getEnumByValue(string $value): self
    {
        foreach (self::cases() as $case){
            if($case->value == $value){
                return $case;
            }
        }

        throw new InvalidArgumentException('This value does not exist.');
    }
}
