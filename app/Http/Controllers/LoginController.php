<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'token' => $request->user()->createToken($request->email)->plainTextToken,
                'data' => [
                    'status code' => 200,
                    'message' => 'Success',
                    'user information' => [
                        $request->user()
                    ]
                ],
            ], 200);
        }

        return response()->json([
            'token' => '',
            'data' => [
                'status code' => 400,
                'message' => 'Bad request',
                'user info' => "{}",
            ]
        ], 400);
    }

    public function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }
}
