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
            'response' => False,
            'seat_count_names' => $request->get('seat_count_names'),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function authenticate(Request $request)
    {
       $credentials = $request->only('username', 'password');

       try {
           if (! $token = JWTAuth::attempt($credentials)) {
               return response()->json(['error' => 'Invalid Credentials'], 400);
           }
       } catch (JWTException $e) {
           return response()->json(['error' => 'could_not_create_token'], 500);
       }

       return response()->json(compact('token'));
    }

    public function getUserDetails()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['error' => 'Token Expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['error' => 'Token Invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['error' => 'Token Absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    public function updateResponse(Request $request, $id)
    {
      if (User::where('id', $id)->exists()) {
          $user = User::find($id);
          $user->response = is_null($request->response) ? $user->response : $request->response;
          $user->save();

          return response()->json(compact('user'), 200);
      } else {
          return response()->json([
              'message' => 'Your details not found'
          ], 404);
      }
    }
}
