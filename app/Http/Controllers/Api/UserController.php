<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\WebPushConfig;

class UserController extends Controller
{



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'required|string|min:10|max:15|unique:users,phone',
            'password'     => 'required|string|min:6|confirmed',
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

        /* =========================
       USER CREATE
       ========================= */
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'password'   => Hash::make($request->password),

            'is_active'  => true,
            'is_blocked' => false,
            'is_online'  => false,

            'wallet_balance' => 0,
            'total_earned'   => 0,

            'rating_avg'   => 0,
            'rating_count' => 0,

            'is_verified'       => false,
            'verification_type' => null,

            'device_type'  => $request->device_type ?? 'web',
            'device_token' => $request->device_token,
            'last_login_at' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        /* =========================
       FETCH ORGANISATION DATA
       ========================= */
        $organisation = Organisation::first();

        $siteName = $organisation?->name ?? config('app.name');
        $siteLogo = $organisation?->logo
            ? asset('storage/' . $organisation->logo)
            : url('\public\storage\organisation_logos\EpIq7o2uQO6i8wWmIsRYxp6FjUXHwOgKdQSCDDNn.png');

        /* =========================
       ADMIN NOTIFICATION
       ========================= */
        try {
            $adminTokens = Admin::whereNotNull('device_token')
                ->pluck('device_token')
                ->toArray();

            if (!empty($adminTokens)) {
                $factory = (new Factory)
                    ->withServiceAccount(base_path(config('services.firebase.credentials')))
                    ->withProjectId(config('services.firebase.project_id'));

                $messaging = $factory->createMessaging();

                $title = "New User Registered | {$siteName}";
                $body  = "{$user->name} ({$user->phone}) joined {$siteName}";

                $message = CloudMessage::new()
                    ->withNotification(Notification::create($title, $body));

                // 🔥 Send to ALL ADMINS
                $messaging->sendMulticast($message, $adminTokens);
            }
        } catch (\Throwable $e) {
            Log::error('Admin notification failed', [
                'error'   => $e->getMessage(),
                'user_id' => $user->id,
            ]);
        }

        /* =========================
       RESPONSE
       ========================= */
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
            'device_type'  => 'required|string|in:android,ios,web',
            'device_token' => 'required|string',
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

        // Add full path to profile image if it exists
        if ($user->profile_image) {
            $user->profile_image_url = asset('storage/' . $user->profile_image);
        } else {
            $user->profile_image_url = asset('images/default-avatar.png');
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile retrieved successfully',
            'data' => [
                'user_uid' => $user->user_uid,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_image_url' => $user->profile_image_url,
                'location' => $user->location,
                'description' => $user->description,
                'cover_image_url' => $user->cover_image_url,
                'is_verified' => $user->is_verified,
                'is_active' => $user->is_active,
                'is_online' => $user->is_online,
                'wallet_balance' => $user->wallet_balance,
                'total_earned' => $user->total_earned,
                'rating_avg' => $user->rating_avg,
                'rating_count' => $user->rating_count,
                'last_login_at' => $user->last_login_at,
                'last_seen_at' => $user->last_seen_at,
                'created_at' => $user->created_at,
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
            'password' => 'sometimes|required|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'location' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
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

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            // Handle profile image upload
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $updateData['profile_image'] = $imagePath;
        }

        if ($request->has('location')) {
            $updateData['location'] = $request->location;
        }

        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }

        if ($request->hasFile('cover_image')) {
            // Handle cover image upload
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
            $updateData['cover_image'] = $coverImagePath;
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
                'location' => $user->location,
                'description' => $user->description,
                'cover_image_url' => $user->cover_image_url,
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

    public function toggleOnlineStatus(Request $request)
    {
        // Logged-in user from token
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user'
            ], 401);
        }

        // Toggle 0/1 automatically
        $newStatus = $user->is_online == 1 ? 0 : 1;

        // Update DB
        $user->update([
            'is_online' => $newStatus,
            'last_seen_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Online status updated successfully',
            'data' => [
                'is_online' => $newStatus,
                'last_seen_at' => $user->last_seen_at,
            ],
        ]);
    }
}
