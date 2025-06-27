<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Friendship;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Geaccepteerde vrienden
        $friends = Friendship::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('friend_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['user', 'friend'])
            ->get()
            ->map(function($friendship) use ($user) {
                return $friendship->user_id === $user->id ? $friendship->friend : $friendship->user;
            });
        // Openstaande uitnodigingen (ontvangen)
        $invitations = Friendship::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();
        // Verstuurde uitnodigingen
        $sentRequests = Friendship::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'accepted', 'blocked'])
            ->with('friend')
            ->get();
        return view('friends', compact('friends', 'invitations', 'sentRequests'));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);
        $user = Auth::user();
        $target = \App\Models\User::where('email', $request->identifier)
            ->orWhere('name', $request->identifier)
            ->first();
        if (!$target || $target->id === $user->id) {
            return redirect()->route('friends.index')->with('error', 'Gebruiker niet gevonden.');
        }
        $exists = \App\Models\Friendship::where(function($q) use ($user, $target) {
            $q->where('user_id', $user->id)->where('friend_id', $target->id);
        })->orWhere(function($q) use ($user, $target) {
            $q->where('user_id', $target->id)->where('friend_id', $user->id);
        })->first();
        if ($exists) {
            return redirect()->route('friends.index')->with('error', 'Vriendschap bestaat al of is in behandeling.');
        }
        \App\Models\Friendship::create([
            'user_id' => $user->id,
            'friend_id' => $target->id,
            'status' => 'pending',
        ]);
        return redirect()->route('friends.index')->with('success', 'Uitnodiging verstuurd!');
    }

    public function accept(Friendship $friendship)
    {
        $user = Auth::user();
        if ($friendship->friend_id !== $user->id) {
            abort(403);
        }
        $friendship->status = 'accepted';
        $friendship->save();
        return redirect()->route('friends.index')->with('success', 'Vriendschap geaccepteerd!');
    }

    public function reject(Friendship $friendship)
    {
        $user = Auth::user();
        if ($friendship->friend_id !== $user->id) {
            abort(403);
        }
        $friendship->status = 'blocked';
        $friendship->save();
        return redirect()->route('friends.index')->with('success', 'Uitnodiging geweigerd.');
    }
}
