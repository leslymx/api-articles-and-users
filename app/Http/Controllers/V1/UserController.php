<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Egulias\EmailValidator\EmailValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
            'status code' => 200,
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
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:50|alpha',
            'last_name' => 'required|string|max:50|alpha',
            'email' => 'required|string|email:dns|unique:users|',
            'password' => 'required',
        ]);

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
            'status_code' => 201,
            'dev' => 'CREATED',
            'token' => $request->user()->createToken($request->email)->plainTextToken,
            'message' => 'User created successfully',
            'user information' => $user
        ], 201);
    }
}
