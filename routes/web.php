<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardApiController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MatchmakingController;

Route::get('/', [WelcomeController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [GameController::class, 'index'])->name('dashboard');

    Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');
    Route::get('/game', [GameController::class, 'index'])->name('game.index');

    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');

    Route::get('/preferences', [PreferenceController::class, 'index'])->name('preferences.index');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');

    Route::post('/friends/invite', [FriendController::class, 'invite'])->name('friends.invite');
    Route::post('/friends/{friendship}/accept', [FriendController::class, 'accept'])->name('friends.accept');
    Route::post('/friends/{friendship}/reject', [FriendController::class, 'reject'])->name('friends.reject');

    Route::post('/game/start', [GameController::class, 'start'])->name('game.start');
    Route::post('/game/{game}/accept', [GameController::class, 'accept'])->name('game.accept');
    Route::post('/game/{game}/attempt', [GameController::class, 'attempt'])->name('game.attempt');
    Route::post('/game/{game}/decline', [GameController::class, 'decline'])->name('game.decline');
    Route::post('/game/{game}/forfeit', [GameController::class, 'forfeit'])->name('game.forfeit');

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/api/active-games', [DashboardApiController::class, 'activeGames'])->name('api.active-games');
    Route::get('/api/game-status/{game}', [DashboardApiController::class, 'gameStatus'])->middleware('auth');

    // Matchmaking routes
    Route::post('/matchmaking/join', [MatchmakingController::class, 'joinQueue'])->name('matchmaking.join');
    Route::get('/matchmaking/check', [MatchmakingController::class, 'checkMatch'])->name('matchmaking.check');

    Route::post('/preferences/update', [PreferenceController::class, 'update'])->name('preferences.update');
});

Route::get('/users/{user}', [ProfileController::class, 'show'])->name('users.show');

require __DIR__.'/auth.php';
