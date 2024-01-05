<?php

namespace App\Livewire;

use App\Interfaces\Fieldable;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

abstract class Form extends Component
{
    public Fieldable|null $fieldableModel = null;

    public function updated($property): void
    {
        $this->syncFieldableModel();
        if(!$this->fieldableModel->isFieldModifiable($property)){
            abort(Response::HTTP_FORBIDDEN);
        }
    }

    protected function syncFieldableModel(): void
    {
        $this->fieldableModel = $this->fieldableModel();
    }

    protected function fieldableModel(): Fieldable|null
    {
        return null;
    }
}
