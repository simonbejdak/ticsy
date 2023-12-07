<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

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

    public static function toIterable(Collection|array $object): array{
        $return = [];

        if($object instanceof Collection){
            foreach ($object->all() as $value){
                $return[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                ];
            }
            return $return;
        }
        if(is_array($object)){
            if(array_is_list($object)){
                foreach ($object as $value){
                    $return[] = ['id' => $value, 'name' => $value];
                }
            }
            else {
                foreach ($object as $key => $value){
                    $return[] = ['id' => $value, 'name' => $key];
                }
            }
            return $return;
        }

        throw new InvalidArgumentException('Method toIterable() only accepts arguments of type Collection or array');
    }
}
