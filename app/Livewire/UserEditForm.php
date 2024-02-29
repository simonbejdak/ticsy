<?php

namespace App\Livewire;

use App\Enums\Location;
use App\Enums\OnHoldReason;
use App\Enums\Priority;
use App\Enums\Status;
use App\Enums\UserStatus;
use App\Helpers\Fields\Bar;
use App\Helpers\Fields\CheckBox;
use App\Helpers\Fields\Fields;
use App\Helpers\Fields\Select;
use App\Helpers\Fields\TextArea;
use App\Helpers\Fields\TextInput;
use App\Models\Group;
use App\Models\Task;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserEditForm extends EditForm
{
    public User $user;
    public Location $location;
    public UserStatus $status;

    public function rules(): array
    {
        return [
            'location' => ['required', Rule::enum(Location::class)],
            'status' => ['required', Rule::enum(UserStatus::class)],
        ];
    }

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->model = $user;
        $this->location = $this->user->location;
        $this->status = $this->user->status;
        $this->setActivities();
    }

    public function save()
    {
        $this->validate();
        $this->user->location = $this->location;
        $this->user->status = $this->status;
        $this->user->save();

        Session::flash('success', 'You have successfully updated the user');
        return redirect()->route('users.edit', $this->user);
    }

    function schema(): Fields
    {
        return new Fields(
            TextInput::make('name')->value($this->user->name),
            TextInput::make('email')->value($this->user->email),
            function () {
                if($this->user->hasPrimaryConfigurationItem()){
                    return TextInput::make('configurationItem')
                        ->value($this->user->configurationItems()->primary()->first()->serial_number)
                        ->anchor(route('userConfiguration-items.edit', $this->user->configurationItems()->primary()->first()));
                } return null;
            },
            Select::make('location')->options(Location::class),
            Select::make('status')->options(UserStatus::class),
            TextInput::make('created')->label('Created at')->value($this->user->created_at->format('d.m.Y h:i:s')),
            TextInput::make('updated')->label('Updated at')->value($this->user->updated_at->format('d.m.Y h:i:s')),
            CheckBox::make('isResolver')->label('Is Resolver')->checkedIf($this->user->isResolver()),
        );
    }

    protected function isFieldDisabled(string $name): bool
    {
        if($this->user->isArchived()){
            return true;
        }

        if(auth()->user()->cannot('update', User::class)){
            return true;
        }

        return match($name){
            'name', 'email', 'configurationItem', 'created', 'updated' => true,
            default => false,
        };
    }

    function tabs(): array
    {
        return [];
    }
}
