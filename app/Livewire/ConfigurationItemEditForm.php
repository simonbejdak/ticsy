<?php

namespace App\Livewire;

use App\Enums\ConfigurationItemStatus;
use App\Enums\ConfigurationItemType;
use App\Enums\Location;
use App\Enums\OperatingSystem;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use App\Models\ConfigurationItem;
use App\Models\Group;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ConfigurationItemEditForm extends EditForm
{
    public ConfigurationItem $configurationItem;
    public $group;
    public Location $location;
    public ConfigurationItemStatus $status;
    public ConfigurationItemType $type;
    public OperatingSystem $operatingSystem;
    public string $comment;

    public function rules(): array
    {
        return [
            'group' => ['required', 'exists:App\Models\Group,id'],
            'location' => ['required', Rule::enum(Location::class)],
            'status' => ['required', Rule::enum(ConfigurationItemStatus::class)],
            'type' => ['required', Rule::enum(ConfigurationItemType::class)],
            'operatingSystem' => ['required', Rule::enum(OperatingSystem::class)],
        ];
    }

    public function mount(ConfigurationItem $configurationItem): void
    {
        $this->configurationItem = $configurationItem;
        $this->model = $configurationItem;
        $this->group = $this->configurationItem->group->id;
        $this->location = $this->configurationItem->location;
        $this->status = $this->configurationItem->status;
        $this->type = $this->configurationItem->type;
        $this->operatingSystem = $this->configurationItem->operating_system;
        $this->comment = '';
        $this->setActivities();
    }

    public function save()
    {
        $this->validate();
        $this->configurationItem->group_id = $this->group;
        $this->configurationItem->location = $this->location;
        $this->configurationItem->status = $this->status;
        $this->configurationItem->type = $this->type;
        $this->configurationItem->operating_system = $this->operatingSystem;
        $this->configurationItem->save();

        if($this->comment !== ''){
            ActivityService::comment($this->configurationItem, $this->comment);
        }

        Session::flash('success', 'You have successfully updated the configuration item');
        return redirect()->route('userConfiguration-items.edit', $this->configurationItem);
    }

    function schema(): Fields
    {
        return new Fields(
            TextInput::make('user')
                ->value($this->configurationItem->user->name),
            Select::make('group')
                ->options(Group::all()),
            Select::make('location')
                ->options(Location::class),
            Select::make('status')
                ->options(ConfigurationItemStatus::class),
            Select::make('type')
                ->options(ConfigurationItemType::class),
            TextInput::make('serialNumber')
                ->value($this->configurationItem->serial_number),
            Select::make('operatingSystem')
                ->options(OperatingSystem::class),
            TextArea::make('comment')
                ->label('Add a comment')
                ->outsideGrid(),
        );
    }

    protected function isFieldDisabled(string $name): bool
    {
        if($this->configurationItem->isArchived()){
            return true;
        }
        return match ($name){
            'user', 'serialNumber' => true,
            default => false,
        };
    }

    function tabs(): array
    {
        return [];
    }
}
