<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $organisation = Organisation::first(); // Assuming one organisation for now

        return view('admin.settings.index', compact('admin', 'organisation'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email,' . Auth::guard('admin')->id(),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin = Auth::guard('admin')->user();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($admin->image && Storage::exists('public/' . $admin->image)) {
                Storage::delete('public/' . $admin->image);
            }
            $data['image'] = $request->file('image')->store('admin_images', 'public');
        }

        $admin->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateOrganisation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'contact1' => 'required|string|max:20',
            'contact2' => 'nullable|string|max:20',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $organisation = Organisation::first();

        if (!$organisation) {
            $organisation = new Organisation();
        }

        $data = $request->only(['name', 'email', 'website', 'contact1', 'contact2', 'address']);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($organisation->logo && Storage::exists('public/' . $organisation->logo)) {
                Storage::delete('public/' . $organisation->logo);
            }
            $data['logo'] = $request->file('logo')->store('organisation_logos', 'public');
        }

        $organisation->fill($data)->save();

        return redirect()->back()->with('success', 'Organisation details updated successfully.');
    }
}
