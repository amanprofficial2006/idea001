<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_token' => 'nullable|string',
            'device_type' => 'nullable|string',
        ]);

        if (
            Auth::guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password,
                'is_active' => 1,
            ])
        ) {
            $admin = Auth::guard('admin')->user();

            // ✅ Update login + device info
            $admin->update([
                'last_login_at' => now(),
                'device_token'  => $request->device_token,
                'device_type'   => $request->device_type ?? 'web',
            ]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or inactive admin',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
