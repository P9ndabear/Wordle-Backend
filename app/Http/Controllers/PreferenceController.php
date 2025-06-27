<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    public function index()
    {
        // Placeholder data
        $preferences = [];
        return view('profile', compact('preferences'));
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $user->setPreference('privacy_profile', $request->has('privacy_profile') ? 'private' : 'public');
        $user->setPreference('notify_email', $request->has('notify_email') ? 'on' : 'off');
        return back()->with('status', 'preferences-updated');
    }
}
