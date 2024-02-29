<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Location;
use App\Enums\ResolverPanelOption;
use App\Enums\ResolverPanelTab;
use App\Enums\UserStatus;
use App\Livewire\Table;
use App\Traits\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Entity, HasRoles, LogsActivity, HasApiTokens, HasFactory, Notifiable;
    public const DEFAULT_PROFILE_PICTURE = 'default_profile_picture.png';

    protected $attributes = [
        'status' => UserStatus::ACTIVE,
        'selected_resolver_panel_tab' => ResolverPanelTab::ALL,
    ];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
        'location' => Location::class,
        'status' => UserStatus::class,
        'selected_resolver_panel_tab' => ResolverPanelTab::class,
    ];

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'caller_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class, 'caller_id');
    }

    public function resolverRequests(): HasMany
    {
        return $this->hasMany(Request::class, 'resolver_id');
    }

    public function favoriteResolverPanelOptions(): HasMany
    {
        return $this->hasMany(FavoriteResolverPanelOption::class);
    }

    public function configurationItems(): HasMany
    {
        return $this->hasMany(ConfigurationItem::class);
    }

    public function tablePersonalizations(): HasMany
    {
        return $this->hasMany(TablePersonalization::class);
    }

    public function tablePersonalization(Table $table): TablePersonalization|null
    {
        return $this->tablePersonalizations()->byTable($table)->first();
    }

    public function isGroupMember(Group $group): bool
    {
        return $this->groups()->where('id', $group->id)->exists();
    }

    public function isResolver(): bool
    {
        return $this->hasRole('resolver');
    }

    public function hasFavoriteResolverPanelOption(ResolverPanelOption $option): bool
    {
        return count($this->favoriteResolverPanelOptions()->where('option', '=', $option)->get()) == 1;
    }

    public function getFavoriteResolverPanelOption(ResolverPanelOption $option): FavoriteResolverPanelOption
    {
        return $this->favoriteResolverPanelOptions()->where('option', '=', $option)->first();
    }

    public static function getSystemUser()
    {
        // the first user being created is System user in UserSeeder, so ID is 1
        return self::findOrFail(1);
    }

    public function getProfilePictureAttribute($value)
    {
        if($value === null){
            return self::DEFAULT_PROFILE_PICTURE;
        }

        return $value;
    }

    public function getFavoriteResolverPanelOptions(): array
    {
        $options = [];
        foreach ($this->favoriteResolverPanelOptions as $option){
            $options[] = $option->option;
        }
        return $options;
    }

    public function getSelectedResolverPanelTab(): ResolverPanelTab
    {
        return $this->selected_resolver_panel_tab;
    }

    function isArchived(): bool
    {
        return $this->status == UserStatus::INACTIVE;
    }

    function hasPrimaryConfigurationItem(): bool
    {
        return count($this->configurationItems()->primary()->get()) >= 1;
    }

    function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'location',
                'status',

            ])
            ->logOnlyDirty();
    }
}
