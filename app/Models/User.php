<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Incident;
use App\Models\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;
    public const DEFAULT_PROFILE_PICTURE = 'default_profile_picture.png';

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'caller_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'caller_id');
    }

    public function resolverRequests(): HasMany
    {
        return $this->hasMany(Request::class, 'resolver_id');
    }

    public function isGroupMember(Group $group)
    {
        return $this->groups()->where('id', $group->id)->exists();
    }

    public function isResolver(): bool
    {
        return $this->hasRole('resolver');
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
}
