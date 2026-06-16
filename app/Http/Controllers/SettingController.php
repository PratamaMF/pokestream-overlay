<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'username'         => 'required|string|max:255|unique:users,username,' . $user->id,
            'password'         => 'nullable|string|min:6',
            'current_password' => 'nullable|string',
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required'     => 'Store or Owner name is required.',
            'username.required' => 'Username is required.',
            'username.unique'   => 'This username has already been taken.',
            'password.min'      => 'The new password must be at least 6 characters.',
            'logo.image'        => 'The uploaded file must be an image.',
            'logo.max'          => 'The logo image size may not be greater than 2 MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('SA-error', 'Validation failed.');
        }

        $isUsernameChanged = $request->username !== $user->username;
        $isNewPasswordFilled = $request->filled('password');

        if ($isUsernameChanged || $isNewPasswordFilled) {
            if (!$request->filled('current_password')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['current_password' => 'Current password is required to change username or password.'])
                    ->with('SA-error', 'Security authentication required.');
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['current_password' => 'The current password you entered is incorrect.'])
                    ->with('SA-error', 'Authentication failed.');
            }
        }

        try {
            $user->name = $request->name;
            $user->username = $request->username;

            if ($isNewPasswordFilled) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('logo')) {
                if ($user->logo && Storage::disk('public')->exists($user->logo)) {
                    Storage::disk('public')->delete($user->logo);
                }
                $user->logo = $request->file('logo')->store('logos', 'public');
            }

            $user->save();

            return redirect()->route('settings.index')->with('SA-success', 'Store settings updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('SA-error', 'Failed to update settings. Error: ' . $e->getMessage());
        }
    }
}