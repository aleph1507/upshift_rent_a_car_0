<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(auth()->user()->locations);
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
//      Location:  'user_id', 'email', 'name', 'address', 'latitude', 'longitude', 'phoneNumber'
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:locations',
            'name' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phoneNumber' => 'nullable|string',
        ]);

        if($validation->fails()) {
            return response()->json(['error' => $validation->errors()->toJson()]);
        }

        $location_data = ['email' => $request->email, 'name' => $request->name, 'address' => $request->address,
            'latitude' => $request->latitude, 'longitude' => $request->longitude, 'phoneNumber' => $request->phoneNumber];

        return response()->json(auth()->user()->locations()->create($location_data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return response()->json($location, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:locations',
            'name' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phoneNumber' => 'nullable|string',
        ]);

        if($validation->fails()) {
            return response()->json(['error' => $validation->errors()->toJson()]);
        }

        $saved_location = auth()->user()->locations->where('id', $location->id)->first();
        if($saved_location === null) {
            return response()->json(['error' => 'No such location']);
        }
        return response()->json($saved_location->update($request->all()));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $saved_location = auth()->user()->locations->where('id', $location->id)->first();
        if($saved_location === null) {
            return response()->json(['error' => 'No such location']);
        }
        return response()->json($saved_location->delete());
    }
}
