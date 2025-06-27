@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif
    <div id="matchmaking-status" style="display: none;" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">Op zoek naar een tegenstander...</span>
        <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-6 w-6 inline-block ml-2"></div>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Dashboard</h2>

    <div class="flex flex-col gap-12">
        <!-- Spel starten -->
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200 mb-12">
            <h3 class="text-lg font-semibold mb-4">Nieuw spel starten</h3>
            @php
                $activeGame = \App\Models\Game::where(function($q) {
                    $q->where('user_id', Auth::id())
                      ->orWhere('opponent_id', Auth::id());
                })
                ->whereIn('status', ['active', 'pending'])
                ->orderByDesc('created_at')
                ->first();
            @endphp
            @if($activeGame)
                <div class="bg-yellow-50 border border-yellow-200 rounded px-4 py-3 mb-4">
                    <p class="text-yellow-800">
                        Je hebt al een actief spel. 
                        <a href="{{ route('game.show', $activeGame->id) }}" class="text-blue-600 hover:text-blue-800 underline">
                            Ga naar je actieve spel
                        </a>
                    </p>
                </div>
            @endif
            @if(!$activeGame)
                <form method="POST" action="{{ route('game.start') }}" class="flex flex-col md:flex-row md:items-center gap-4">
                    @csrf
                    <input type="hidden" name="type" value="friend">
                    <select name="opponent_id" class="border rounded px-3 py-2" required>
                        <option value="">Kies een vriend...</option>
                        @foreach(Auth::user()->allFriends() as $friend)
                            <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-200 text-black px-4 py-2 rounded hover:bg-blue-300">Tegen vriend</button>
                </form>
                <form method="POST" action="{{ route('game.start') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="type" value="random">
                    <button type="submit" class="bg-green-200 text-black px-4 py-2 rounded hover:bg-green-300">Tegen willekeurige tegenstander</button>
                </form>
            @endif
        </div>

        <!-- Actieve spellen -->
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200 mb-12">
            <h3 class="text-lg font-semibold mb-4">Actieve spellen</h3>
            <ul id="active-games-list" class="space-y-2">
                <li class="text-gray-500">Geen actieve spellen</li>
            </ul>
        </div>

        <!-- Leaderboards -->
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200 mb-12">
            <h3 class="text-lg font-semibold mb-4">Leaderboard</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-md font-semibold mb-2 text-gray-700">Vandaag</h4>
                    <ul class="space-y-1">
                        @forelse($leaderboardToday as $user)
                            <li class="text-gray-800">
                                <a href="{{ route('users.show', $user) }}" class="text-blue-700 hover:underline">{{ $user->name }}</a>: {{ $user->winsToday() }} wins
                            </li>
                        @empty
                            <li class="text-gray-500">No data</li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-2 text-gray-700">Deze Week</h4>
                    <ul class="space-y-1">
                        @forelse($leaderboardThisWeek as $user)
                            <li class="text-gray-800">
                                <a href="{{ route('users.show', $user) }}" class="text-blue-700 hover:underline">{{ $user->name }}</a>: {{ $user->winsThisWeek() }} wins
                            </li>
                        @empty
                            <li class="text-gray-500">No data</li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-2 text-gray-700">Allertijden</h4>
                    <ul class="space-y-1">
                        @forelse($leaderboardAllTime as $user)
                            <li class="text-gray-800">
                                <a href="{{ route('users.show', $user) }}" class="text-blue-700 hover:underline">{{ $user->name }}</a>: {{ $user->winsAllTime() }} wins
                            </li>
                        @empty
                            <li class="text-gray-500">No data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border border-gray-200 mb-12">
            <!-- Spelgeschiedenis -->
            <div class="bg-white shadow rounded-lg p-6 col-span-2">
                <h3 class="text-lg font-semibold mb-4">Spelgeschiedenis</h3>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Tegenstander</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Resultaat</th>
                            <th class="py-2">Woord</th>
                            <th class="py-2">Datum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($games as $game)
                            <tr class="border-t">
                                <td class="py-2">
                                    @if($game->user_id === Auth::id())
                                        <a href="{{ route('users.show', $game->opponent) }}" class="text-blue-700 hover:underline">{{ $game->opponent->name }}</a>
                                    @else
                                        <a href="{{ route('users.show', $game->user) }}" class="text-blue-700 hover:underline">{{ $game->user->name }}</a>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                        @if($game->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($game->status === 'active') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $game->status }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    @if($game->status === 'finished')
                                        @if($game->winner_id === Auth::id())
                                            <span class="text-green-600">Gewonnen</span>
                                        @elseif($game->winner_id === null)
                                            <span class="text-gray-600">Gelijkspel</span>
                                        @else
                                            <span class="text-red-600">Verloren</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if(isset($game->word))
                                        <span class="font-mono uppercase">{{ $game->word }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-2 text-gray-500">{{ $game->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2">
                                    @if($game->status === 'pending' && $game->opponent_id === Auth::id())
                                        <div class='flex gap-2'>
                                            <form method="POST" action="{{ route('game.accept', $game->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800">Accepteren</button>
                                            </form>
                                            <form method="POST" action="{{ route('game.decline', $game->id) }}" class="inline ml-2">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800">Weigeren</button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ route('game.show', $game->id) }}" class="text-blue-600 hover:text-blue-800"></a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-2 text-gray-500 text-center">Nog geen spellen gespeeld</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vriendenlijst nu zonder mt-8 -->
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Vriendenlijst</h3>
            <ul>
                @forelse(Auth::user()->allFriends() as $friend)
                    <li class="text-gray-800">
                        <a href="{{ route('users.show', $friend) }}" class="text-blue-700 hover:underline">{{ $friend->name }}</a>
                    </li>
                @empty
                    <li class="text-gray-500">Je hebt nog geen vrienden</li>
                @endforelse
            </ul>
            <a href="{{ route('friends.index') }}" class="inline-block mt-4 bg-indigo-200 text-black px-4 py-2 rounded hover:bg-indigo-300">Vriend toevoegen</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const myUserId = {{ Auth::id() }};
    const csrfToken = '{{ csrf_token() }}';

    function fetchActiveGames() {
        fetch('/api/active-games')
            .then(response => response.json())
            .then(data => {
                let ul = document.getElementById('active-games-list');
                if (!ul) return;
                ul.innerHTML = '';
                if (data.length === 0) {
                    ul.innerHTML = '<li class="text-gray-500">Geen actieve spellen</li>';
                } else {
                    data.forEach(game => {
                        let tegenstander = (game.user_id === myUserId) ? game.opponent.name : game.user.name;
                        let status = game.status.charAt(0).toUpperCase() + game.status.slice(1);
                        let action = '';
                        if (game.status === 'pending' && game.opponent_id === myUserId) {
                            action = `<div class='flex gap-2'>
                                <form method='POST' action='/game/${game.id}/accept'>
                                    <input type='hidden' name='_token' value='${csrfToken}'>
                                    <button type='submit' class='text-green-600 hover:text-green-800'>Accepteren</button>
                                </form>
                                <form method='POST' action='/game/${game.id}/decline'>
                                    <input type='hidden' name='_token' value='${csrfToken}'>
                                    <button type='submit' class='text-red-600 hover:text-red-800'>Weigeren</button>
                                </form>
                            </div>`;
                        } else {
                            action = `<a href='/game/${game.id}' class='text-blue-600 hover:text-blue-800'></a>`;
                        }
                        ul.innerHTML += `
                            <li class="flex justify-between items-center border-b py-2">
                                <span>${tegenstander}</span>
                                <span class="text-xs">${status}</span>
                                <span>${action}</span>
                            </li>
                        `;
                    });
                }
            });
    }

    const isSearching = @json(session('searching', false));
    const matchmakingStatusEl = document.getElementById('matchmaking-status');
    
    if (isSearching) {
        matchmakingStatusEl.style.display = 'block';
        let intervalId = setInterval(checkMatch, 3000);

        function checkMatch() {
            fetch('{{ route("matchmaking.check") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.matched) {
                        clearInterval(intervalId);
                        matchmakingStatusEl.innerHTML = 'Tegenstander gevonden! Spel wordt gestart...';
                        window.location.href = `/game/${data.game_id}`;
                    }
                })
                .catch(error => {
                    console.error('Error checking for match:', error);
                    clearInterval(intervalId);
                    matchmakingStatusEl.innerHTML = 'Er is een fout opgetreden bij het zoeken naar een spel.';
                    matchmakingStatusEl.classList.remove('bg-yellow-100', 'border-yellow-400', 'text-yellow-700');
                    matchmakingStatusEl.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                });
        }
    }

    fetchActiveGames();
    setInterval(fetchActiveGames, 5000);
});
</script>
@endsection
