<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * User Login
     *
     * @param  Request $request
     * @return json
     */
    public function login(LoginUserRequest $request)
    {

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'success' => true,
            'message' => 'User Logged In successfully.',
            'token' => $user->createToken('API Token')->plainTextToken
        ], 200);
    }

    /**
     * User Registration
     *
     * @param  Request $request
     * @return json
     */
    public function register(StoreUserRequest $request)
    {
        $request->validated($request->only(['name', 'email', 'password']));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.'
        ], 200);
    }

    /**
     * User Logout
     *
     * @param  Request $request
     * @return json
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully.'
        ], 200);
    }
}
