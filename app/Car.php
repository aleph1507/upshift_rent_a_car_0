<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'location_id', 'brand','model','year','typeOfFuel', 'status', 'pricePerDay'
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeNotAvailable($query)
    {
        return $query->where('status', 'not_available');
    }

    public function scopeRented($query)
    {
        return $query->where('status', 'rented');
    }

    public function priceInRange($query, $low=-INF, $high=INF)
    {
        return $query->where([
            ['pricePerDay', '>=', $low],
            ['pricePerDay', '<=', $high]
        ]);
    }
}
