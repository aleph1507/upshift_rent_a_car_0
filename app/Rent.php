<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    protected $fillable = [
        'car_id', 'user_id', 'renting_location_id', 'returning_location_id', 'rented_at', 'returned_at'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeActive($query)
    {
        return $query->where('returned_at', null);
    }

    public function scopeFinished($query)
    {
        return $query->where('returned_at', '<>', null);
    }
}
