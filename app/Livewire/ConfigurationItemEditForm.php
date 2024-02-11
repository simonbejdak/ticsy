<?php

namespace App\Livewire;

use App\Enums\ConfigurationItemStatus;
use App\Enums\ConfigurationItemType;
use App\Enums\Location;
use App\Enums\OperatingSystem;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use App\Models\ConfigurationItem;
use App\Models\Group;
use App\Services\ActivityService;
use App\Traits\HasFields;
use Illuminate\Support\Facades\Session;
use App\Helpers\Fields\Fields;

class ConfigurationItemEditForm extends EditForm
{
    use HasFields;

    public ConfigurationItem $configurationItem;
    public Group $group;
    public Location $location;
    public ConfigurationItemStatus $status;
    public ConfigurationItemType $type;
    public OperatingSystem $operatingSystem;
    public string $comment;

    public function mount(ConfigurationItem $configurationItem): void
    {
        $this->configurationItem = $configurationItem;
        $this->model = $configurationItem;
        $this->group = $this->configurationItem->group;
        $this->location = $this->configurationItem->location;
        $this->status = $this->configurationItem->status;
        $this->type = $this->configurationItem->type;
        $this->operatingSystem = $this->configurationItem->operating_system;
        $this->comment = '';
        $this->setActivities();
    }

    public function save()
    {
        if($this->comment !== ''){
            ActivityService::comment($this->configurationItem, $this->comment);
        }

        Session::flash('success', 'You have successfully updated the configuration item');
        return redirect()->route('configuration-items.edit', $this->configurationItem);
    }

    function fields(): Fields
    {
        return new Fields(
            TextInput::make('user')
                ->value($this->configurationItem->user->name)
                ->disabled(),
            Select::make('group')
                ->options(Group::all())
                ->disabled(),
            Select::make('location')
                ->options(Location::class)
                ->disabled(),
            Select::make('status')
                ->options(ConfigurationItemStatus::class)
                ->disabled(),
            Select::make('type')
                ->options(ConfigurationItemType::class)
                ->disabled(),
            TextInput::make('serialNumber')
                ->value($this->configurationItem->serial_number)
                ->disabled(),
            Select::make('operatingSystem')
                ->options(OperatingSystem::class)
                ->disabled(),
            TextArea::make('comment')
                ->label('Add a comment')
                ->outsideGrid(),
        );
    }
}
