<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
        'phone',
        'nik',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'religion',
        'profile_photo',
        'provinces',
        'provincesId',
        'regencies',
        'regenciesId',
        'districts',
        'districtsId',
        'villages',
        'villagesId',
        'status_login',  //0 tidak aktif, 1 aktif
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
        ];
    }

    /**
    * Get the sessions for the user.
    */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

     // Helper method to check if user is admin
    public function isAdmin()
    {
        return $this->roles === 'Admin';
    }

    // Helper method to check if user is mahasiswa
    public function isMahasiswa()
    {
        return $this->roles === 'Mahasiswa';
    }
}
