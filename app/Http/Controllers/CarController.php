<?php

namespace App\Http\Controllers;

use App\Car;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cars = null;
        $searchParams = [];
        foreach(['brand', 'model', 'year'] as $q) {
            if($request->has($q))
                $searchParams[$q] = $request->$q;
        }

        if($searchParams !== [])
            $cars = Car::where($searchParams);

        if($request->has('filter') && in_array($request->filter, ['status', 'location', 'priceRange'])) {
            if ($request->has('filter')) {
                if($cars === null){
                    if($request->filter === 'priceRange'){
                        return Car::priceInRange($request->min ?? 0, $request->max ?? INF)->get();
                    }
                    return Car::where($request->filter, 'LIKE', "%$request->filterBy%")->get();
                } else {
                    if($request->filter === 'priceRange') {
                        return $cars->priceInRange($request->min ?? 0, $request->max ?? INF)->get();
                    }
                    return $cars->where($request->filter, 'LIKE', "%$request->filterBy%")->get();
                }

            }
        }

        return $cars === null ? response()->json(auth()->user()->cars) : $cars->get();
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
        //      Car:  'brand','model','year','typeOfFuel', 'status', 'pricePerDay'
        if(($valid = $this->validateCarRequest($request)) !== true) {
            return response()->json($valid, 400);
        }

        if(!($location = $this->getLocationCheckOwnership($request->location_id))) {
            return response()->json(['error' => 'No such location'], 400);
        }

        return response()->json($location->cars()->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return response()->json($car, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Car $car)
    {
        if(($valid = $this->validateCarRequest($request)) !== true) {
            return response()->json($valid, 400);
        }

        if(!($location = $this->getLocationCheckOwnership($request->location_id))) {
            return response()->json(['error' => 'No such location'], 400);
        }

        if(!(($car = $this->getCarCheckOwnership($car)) instanceof Car)) {
            return response()->json(['error' => 'No such car'], 400);
        }

        if($location->cars()->where('id', $car->id)->first()->update($request->all())) {
            return response()->json(['success' => 'Car updated'], 200);
        } else {
            return response()->json(['error' => 'Could not update car'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {

        $saved_car = $this->getCarCheckOwnership($car);
        if($saved_car instanceof Car) {
            return response()->json($saved_car->delete());
        } else {
            return response()->json(['error' => 'No such car'], 400);
        }
    }



    public function validateCarRequest(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'location_id' => 'required|integer',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'typeOfFuel' => 'required|string',
            'status' => 'required|in:available,not_available,rented',
            'pricePerDay' => 'required|numeric',
        ]);

        if($validation->fails()) {
            return $validation->errors()->toJson();
        } else {
            return true;
        }
    }

    public function getCarCheckOwnership(Car $car)
    {
        $saved_car = auth()->user()->cars->where('id', $car->id)->first();

        return $saved_car ?? false;
    }

    public function getLocationCheckOwnership($location_id)
    {
        $users_location = auth()->user()->locations->where('id', $location_id)->first();

        return $users_location ?? false;
    }
}
