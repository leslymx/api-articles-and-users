<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'statusCode' => 200,
            'dev' => 'OK',
            'message' => 'Successfully obtained users',
            'user information' => $this->user->paginate(5)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create(
            [
                "name" => $request->name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
            ]
        );
        $user->save();

        return response()->json([
            'statusCode' => 201,
            'dev' => 'CREATED',
            'message' => 'User created successfully',
            'token' => $request->user()->createToken($request->email)->plainTextToken,
            'user information' => $user
        ], 201);
    }
}
