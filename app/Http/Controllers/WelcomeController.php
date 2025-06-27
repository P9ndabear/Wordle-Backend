<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class WelcomeController extends Controller
{
    public function index()
    {
        $leaderboardToday = User::all()->sortByDesc(function($user) {
            return $user->winsToday();
        })->take(10);

        $leaderboardThisWeek = User::all()->sortByDesc(function($user) {
            return $user->winsThisWeek();
        })->take(10);

        $leaderboardAllTime = User::all()->sortByDesc(function($user) {
            return $user->winsAllTime();
        })->take(10);

        return view('welcome', [
            'leaderboardToday' => $leaderboardToday,
            'leaderboardThisWeek' => $leaderboardThisWeek,
            'leaderboardAllTime' => $leaderboardAllTime,
        ]);
    }
}
