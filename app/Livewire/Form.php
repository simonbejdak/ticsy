<?php

namespace App\Livewire;

use App\Helpers\Fields\Fields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

abstract class Form extends Component
{
    public Model $model;

    abstract protected function fields(): Fields;
    abstract protected function schema(): Fields;
    abstract protected function isFieldDisabled(string $name): bool;
    abstract function tabs(): array;

    public function hydrate(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    function updated($property): void
    {
        if($this->isFieldDisabled($property)){
            abort(Response::HTTP_FORBIDDEN);
        }
    }

    // I could've placed this logic on Field class itself, but then I would have
    // to specify ->requiredIf() call each time I create a Field class,
    function isFieldMarkedAsRequired($fieldName): bool{
        return $this->isFieldRequired($fieldName) && empty($this->{$fieldName});
    }

    protected function isFieldRequired($fieldName): bool
    {
        $rules = $this->getRules();

        // Check if the field has validation rules defined in the first place,
        // as without this check there is a situation where Laravel throws
        // an exception, when the field is not defined in rules() method
        if(!array_key_exists($fieldName, $rules)){
            return false;
        }

        if (in_array('required', $rules[$fieldName])) {
            return true;
        } else {
            foreach ($rules as $rule) {
                if ($rule instanceof RequiredIf) {
                    if ($rule->condition) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
