<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\LoginResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'token' => $request->user()->createToken($request->email)->plainTextToken,
                'data' => [
                    'statusCode' => 200,
                    'dev' => 'OK',
                    'message' => 'Successful login',
                    'user information' => new LoginResource($request->user())
                ],
            ], 200);
        }

        return response()->json([
            'token' => '',
            'data' => [
                'status code' => 400,
                'message' => 'Bad request',
                'user information' => "{}",
            ]
        ], 400);
    }
}
