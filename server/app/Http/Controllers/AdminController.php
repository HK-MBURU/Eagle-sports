<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function settings()
    {
        $settings = Setting::all()->keyBy('key');
        $user     = auth()->user();

        return view('admin.settings.index', compact('settings', 'user'));
    }

    public function updateSettings(Request $request)
    {
        $tab = $request->input('tab', 'site');

        if ($tab === 'account') {
            $data = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            ]);

            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:8|confirmed']);
                $data['password'] = Hash::make($request->password);
            }

            auth()->user()->update($data);

            return back()->with('success', 'Account updated successfully.')->with('active_tab', 'account');
        }

        // Site / Social / Content settings
        $keys = [
            'site_name', 'site_tagline', 'contact_email', 'contact_phone',
            'contact_address', 'about_blurb',
            'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url',
            'stories_per_page',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'Settings saved.')->with('active_tab', $tab);
    }
}
