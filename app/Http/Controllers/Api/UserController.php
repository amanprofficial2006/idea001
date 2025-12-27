<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|min:10|max:15|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',

            'device_type'  => 'nullable|string|in:android,ios,web',
            'device_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),

            'is_active'    => true,
            'is_blocked'   => false,
            'is_online'    => false,

            'wallet_balance' => 0,
            'total_earned'   => 0,

            'rating_avg'   => 0,
            'rating_count' => 0,

            'is_verified'      => false,
            'verification_type' => null,

            'device_type'  => $request->device_type,
            'device_token' => $request->device_token,
            'last_login_at' => now(),
        ]);

        // OPTIONAL: if you are using Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully',
            'data'    => [
                'user_uid' => $user->user_uid,
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'token'    => $token,
            ],
        ], 200);
    }

    
}
