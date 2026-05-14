<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle user login via Laravel Sanctum.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Attempt to authenticate the user using Auth::attempt
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Return JSON error response if credentials are invalid
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid login credentials.'
            ], 401);
        }

        // 3. Retrieve the authenticated user
        $user = User::where('email', $request->email)->firstOrFail();

        // 4. Generate a new Sanctum plainTextToken
        $token = $user->createToken('NoteHubAuthToken')->plainTextToken;

        // 5. Return successful JSON response with token and user data
        return response()->json([
            'token'  => $token,
            'user'   => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }

    /**
     * Handle user logout and revoke token.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully.'
        ]);
    }

    /**
     * Handle user registration.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);

        $token = $user->createToken('NoteHubAuthToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }
}