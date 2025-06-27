<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\User;

class MatchmakingController extends Controller
{
    public function checkMatch(Request $request)
    {
        $user = User::find(Auth::id());

        // Kijk of er een nieuw, actief spel is gestart waar de gebruiker de tegenstander is
        // en die nog niet in de wachtrij staat. Dit duidt op een nieuwe match.
        $game = Game::where('opponent_id', $user->id)
                    ->where('status', 'active')
                    // Check of de gebruiker nog in de wachtrij 'stond'
                    // Dit voorkomt dat we oude, al gestarte games als nieuwe match zien
                    ->whereHas('initiator', function ($query) {
                        $query->where('is_in_queue', false); 
                    })
                    ->latest('started_at')
                    ->first();
        
        if ($game) {
            // Match gevonden, haal de gebruiker uit de queue
            $user->is_in_queue = false;
            $user->queued_at = null;
            $user->save();
            return response()->json(['matched' => true, 'game_id' => $game->id]);
        } else {
            return response()->json(['matched' => false]);
        }
    }
} 