<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    const LIST_AKSES = ['Administrator', 'User'];
    const BASE_ROUTES = [
        'Administrator' => 'admin',
        'User' => 'user',
    ];
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'reset_token',
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

    public function akses()
    {
        return $this->hasOne(UserAkses::class);
    }

    public function list_akses()
    {
        return $this->hasMany(UserAkses::class);
    }

}
