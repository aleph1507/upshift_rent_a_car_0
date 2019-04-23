<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
//use function MongoDB\BSON\toJSON;
use App\Http\Resources\User as UserResource;

class PassportController extends Controller
{
    public function register(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'dateOfBirth' => 'date',
            'phoneNumber' => 'nullable|string',
            'role' => 'in:business,customer',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if($validation->fails()) {
            return response()->json(['error' => $validation->errors()->toJson()]);
        }

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'dateOfBirth' => $request->dateOfBirth,
            'phoneNumber' => $request->phoneNumber,
            'role' => $request->role,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $this->getUserToken($user);


        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            return response()->json(['token' => $token = $this->getUserToken(auth()->user())], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function profile()
    {
//        return response()->json(['user' => auth()->user()], 200);
        return (new UserResource(auth()->user()))
            ->response()
            ->setStatusCode(200);
    }

    public function logout (Request $request) {
        if($token = $request->user()->token()) {
            $token->revoke();
            $response = 'You have been logged out!';
            return response($response, 200);
        } else {
            return response()->json(['status' => "not logged in"], 401);
        }
    }

    public function getUserToken($user)
    {
        if($user->role === 'business') {
            $token = $user->createToken('rac0',
                ['overview_locations', 'create_locations', 'view_locations', 'update_locations', 'delete_locations',
                    'search_locations', 'overview_cars', 'create_cars', 'view_cars', 'update_cars', 'delete_cars', 'search_cars'])->accessToken;
//            return response()->json(['token' => $token], 200);
            return $token;
        } else {
            $token = $user->createToken('rac0', ['view_locations', 'search_locations', 'view_cars', 'search_cars'])->accessToken;
            return $token;
        }
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'dateOfBirth' => 'date',
            'phoneNumber' => 'nullable|string',
            'role' => 'in:business,customer',
            'email' => 'required|unique:users,email,'.auth()->user()->id,
            'password' => 'required|min:6',
        ]);

        if($validation->fails()) {
            return response()->json(['error' => $validation->errors()->toJson()]);
        }

        if($request->role === 'customer' && auth()->user()->role === 'business' && (auth()->user()->locations()->count() > 0))
            return response()->json(['error' => 'Cannot change role to customer']);

        $updated_user = [
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'dateOfBirth' => $request->dateOfBirth,
            'phoneNumber' => $request->phoneNumber,
            'role' => $request->role,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

//        return auth()->user();

        return response()->json(auth()->user()->update($updated_user));
    }
}
