<?php

namespace App\Http\Controllers\admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Make sure to import the User model if needed

class ProfileController extends Controller
{
    public $data = [];

    public function __construct()
    {
        $this->data = [
            'moduleName' => "Profile",
            'view'       => 'admin.profile.',
            'route'      => 'profile'
        ];
    }

    // Display the profile edit form
    public function edit()
    {
        return view($this->data['view'] . 'index', $this->data);
    }

    // Update the user profile
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update user information
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Handle profile picture upload
        if ($request->hasFile('profile')) {
            // Delete old profile picture if exists
            if ($user->profile && Storage::exists($user->profile)) {
                Storage::delete($user->profile);
            }

            // Store new profile picture
            $profilePicturePath = $request->file('profile')->store('public/profiles');
            $user->profile = str_replace('public/', '', $profilePicturePath);
        }

        // Save the updated user information
        $user->save();

        // Redirect with a success message
        // return redirect()->route($this->data['route'] . '.index')->with('success', 'Profile updated successfully!');
        Helper::successMsg('custom', 'Profile updated successfully!');
        return redirect(route($this->data['route'] . '.index'));
    }

    // Display the password update form
    public function showPasswordForm()
    {
        return view($this->data['view'] . 'password', $this->data);
    }

    // Update the user password
    public function updatePassword(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Check if the current password is correct
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($validatedData['new_password']);
        $user->save();

        // Redirect with a success message
        Helper::successMsg('custom', 'Password updated successfully!');
        return redirect(route($this->data['route'] . '.index'));
    }
}
