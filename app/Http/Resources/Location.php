<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Location extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'owner' => $this->user->email,
            'email' => $this->email,
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phoneNumber' => $this->phoneNumber,
            'totalCars'=> $this->cars->count(),
            'availableCars' => $this->cars()->available()->count(),
            'rentedCars' => $this->cars()->rented()->count()
        ];
    }
}
