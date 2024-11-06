<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $valid = [
                'email' => 'required|email',
                'password' => 'required',
            ];

            $validator = Validator::make($request->all(), $valid);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $credentials = $request->only('email', 'password');


            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = auth()->user();

            $token = JWTAuth::claims(['status' => $user->role])->fromUser($user);

            return response()->json([
                'token_type' => 'bearer',
                'access_token' => $token,
            ]);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
