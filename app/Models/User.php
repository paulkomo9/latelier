<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\AuditTrail;
use App\Http\Traits\Lockable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, Lockable;

    protected $tagName = 'Users Control Centre';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'profile_pic',
        'email',
        'password',
        'lockout_time',
        'email_verified_at',
        'password_changed_at',
        'timezone',
        'last_login_time',
        'user_status',
        'online_status',
        'is_super_admin',
        'language',
        'api_token',
        'deleted_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // relationship with user_packagess
    public function userPackages()
    {
        return $this->hasMany(UserPackages::class);
    }
}
