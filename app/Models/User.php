<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;
    public const DEFAULT_PROFILE_PICTURE = 'default_profile_picture.jpg';

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

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function getSystemUser()
    {
        // the first user being created is System user in UserSeeder, so ID should be 1
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
