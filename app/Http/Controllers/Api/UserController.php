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

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'    => 'required|string|exists:users,phone',
            'password' => 'required|string',
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

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (!$user->is_active || $user->is_blocked) {
            return response()->json([
                'status'  => false,
                'message' => 'Account is inactive or blocked',
            ], 403);
        }

        // Update device info and last login
        $user->update([
            'device_type'  => $request->device_type ?? $user->device_type,
            'device_token' => $request->device_token ?? $user->device_token,
            'last_login_at' => now(),
            'is_online'    => true,
        ]);

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'data'    => [
                'user_uid' => $user->user_uid,
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'profile_image_url' => $user->profile_image_url,
                'is_verified' => $user->is_verified,
                'token'    => $token,
            ],
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Profile retrieved successfully',
            'data' => [
                'user_uid' => $user->user_uid,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_image_url' => $user->profile_image_url,
                'is_verified' => $user->is_verified,
                'is_active' => $user->is_active,
                'is_online' => $user->is_online,
                'wallet_balance' => $user->wallet_balance,
                'total_earned' => $user->total_earned,
                'rating_avg' => $user->rating_avg,
                'rating_count' => $user->rating_count,
                'last_login_at' => $user->last_login_at,
                'last_seen_at' => $user->last_seen_at,
            ],
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updateData = [];

        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->has('email')) {
            $updateData['email'] = $request->email;
        }

        if ($request->hasFile('profile_image')) {
            // Handle profile image upload
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $updateData['profile_image'] = $imagePath;
        }

        $user->update($updateData);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user_uid' => $user->user_uid,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_image_url' => $user->profile_image_url,
                'is_verified' => $user->is_verified,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        // Mark user as offline
        $user->update([
            'is_online' => false,
            'last_seen_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}
