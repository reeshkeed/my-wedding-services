<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'seat_count' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'username' => Str::slug($request->get('name'), '.'),
            'password' => Str::random(5),
            'seat_count' => $request->get('seat_count'),
            'response' => False
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function authenticate(Request $request)
    {
       $credentials = $request->only('username', 'password');

       try {
           if (! $token = JWTAuth::attempt($credentials)) {
               return response()->json(['error' => 'invalid_credentials'], 400);
           }
       } catch (JWTException $e) {
           return response()->json(['error' => 'could_not_create_token'], 500);
       }

       return response()->json(compact('token'));
    }
}
