<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
<<<<<<< HEAD
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
=======

class User extends Authenticatable
{
    use Notifiable;
>>>>>>> 1128fdfcda7eb849a5bb998630ee1c410bd7a1d8

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
<<<<<<< HEAD
        'firstName', 'lastName', 'dateOfBirth', 'phoneNumber', 'email', 'password', 'role'
=======
        'name', 'email', 'password',
>>>>>>> 1128fdfcda7eb849a5bb998630ee1c410bd7a1d8
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
<<<<<<< HEAD

    public function locations()
    {
        return $this->hasMany('App\Location');
    }

    public function cars()
    {
        return $this->hasManyThrough('App\Car', 'App\Location');
    }

    public function rents()
    {
        return $this->hasMany('App\Rent');
    }
=======
>>>>>>> 1128fdfcda7eb849a5bb998630ee1c410bd7a1d8
}
