<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $fillable = [
        'user_id', 'email', 'name', 'address', 'latitude', 'longitude', 'phoneNumber'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
