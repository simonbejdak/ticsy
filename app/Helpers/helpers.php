<?php

use App\Enums\Status;
use App\Traits\HasFields;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

function get_class_name($object): string
{
    return (new ReflectionClass($object))->getShortName();
}

function addSpacesBeforeUppercase($value): string {
    return preg_replace('/([A-Z])/', ' $1', $value);
}

function makeDisplayName($name): string{
    $name = addSpacesBeforeUppercase($name);
    $name = strtolower($name);

    return ucfirst($name);
}

function toIterable(string|Collection|array $object): array{
    $return = [];

    if(is_a($object, UnitEnum::class, true)){
        foreach($object::cases() as $case){
            $return[] = [
                'id' => $case->value,
                'name' => $case->value,
            ];
        }
        return $return;
    }

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

    throw new InvalidArgumentException('Method toIterable() only accepts either enums, or arguments of type Collection, and array.');
}

function hasTrait(string $class, $object): bool
{
    return in_array($class, class_uses_recursive($object));
}
