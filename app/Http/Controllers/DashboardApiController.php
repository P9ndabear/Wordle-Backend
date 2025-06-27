<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardApiController extends Controller
{
    public function activeGames()
    {
        $user = auth()->user();
        $games = \App\Models\Game::with(['user', 'opponent'])
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('opponent_id', $user->id);
            })
            ->whereIn('status', ['active', 'pending'])
            ->orderByDesc('created_at')
            ->get();
        return response()->json($games);
    }

    public function gameStatus($game)
    {
        $game = \App\Models\Game::findOrFail($game);
        return response()->json(['status' => $game->status]);
    }
} 