<?php

namespace App\Http\Controllers;

use App\Car;
use App\Location;
use App\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(auth()->user()->rents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // rents a car
        $validation = Validator::make($request->all(), [
            'rented_at' => 'required|date',
            'car_id' => 'required|integer'
        ]);

        if($validation->fails()) {
            return response()->json(['error' => $validation->errors()->toJson()]);
        }

        if(auth()->user()->rents()->active()->count() > 0)
            return response()->json(['error' => 'You already have a rented car'], 425);

        $car = Car::find($request->car_id);

        if($car === null)
            return response()->json(['error' => 'No such car'], 400);

        if($car->status !== 'available')
            return response()->json(['error' => 'Car cannot be rented at the moment'], 202);

        $car->status = 'rented';
        $car->save();

//        return $car->id;

        return auth()->user()->rents()->create(['car_id' => $request->car_id, 'renting_location_id' => $car->location->id, 'rented_at' => $request->rented_at]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function show(Rent $rent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function edit(Rent $rent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rent $rent)
    {
        // returns a rented car
        $validation = Validator::make($request->all(), [
            'car_id' => 'required|integer',
            'returning_location_id' => 'required|integer',
            'returned_at' => 'required|date'
        ]);

        if($validation->fails()) {
            return response()->json(['error' => $validation->errors()->toJson()]);
        }

        // ? check if renting_location->user == returning_location->user

        if($rent->user->id !== auth()->user()->id)
            return response()->json(['error' => 'Unauthorized'], 401);

        if($request->car_id != $rent->car_id)
            return response()->json(['error' => 'Wrong car'], 400);

        if(!$location = Location::find($request->returning_location_id))
            return response()->json(['error' => 'No such location'], 400);

        if(!$car = Car::find($request->car_id))
            return response()->json(['error' => 'No such car'], 400);



        if($rent->update(['returning_location_id' => $location->id, 'returned_at' => $request->returned_at])){
            $car->location_id = $location->id;
            $car->status = 'available';
            $car->save();
            return response()->json(['success' => 'Car returned'], 200);
        } else {
            return response()->json(['error' => 'Car cannot be returned'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rent $rent)
    {
        if(!$location = Location::find($rent->renting_location_id))
            return response()->json(['error' => 'Wrong renting location'], 400);

        if($location->user->id !== auth()->user()->id)
            return response()->json(['error' => 'Unauthorized'], 401);

        return response()->json($rent->delete());
    }
}
