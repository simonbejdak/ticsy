<?php

namespace App\Livewire;

use App\Interfaces\Fieldable;
use http\Exception\InvalidArgumentException;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

abstract class Form extends Component
{
    public Fieldable|null $fieldableModel = null;
    public array $tabs;

    function boot(){
        $this->checkIfTabsAreValid($this->tabs);
    }

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

    protected function checkIfTabsAreValid(array $tabs): void
    {
        $validTabs = ['activities', 'tasks'];

        foreach ($tabs as $tab){
            if(!in_array($tab, $validTabs)){
                throw new \InvalidArgumentException('The ' . $tab . ' does not exist in ticketing system.');
            }
        }
    }
}
