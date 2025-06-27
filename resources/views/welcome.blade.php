@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Welkom bij Wordle Multiplayer!
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Speel Wordle tegen je vrienden of willekeurige tegenstanders.<br>
                Voeg vrienden toe, bekijk je spelgeschiedenis en klim naar de top van het leaderboard!
            </p>
        </div>
        
        <!-- Leaderboards -->
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200 mb-12">
            <h3 class="text-lg font-semibold mb-4">Leaderboard</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-md font-semibold mb-2 text-gray-700">Vandaag</h4>
                    <ul class="space-y-1">
                        @forelse($leaderboardToday as $user)
                            <li class="text-gray-800 flex items-center">
                                <a href="{{ route('users.show', $user) }}" class="max-w-xs truncate block text-blue-700 hover:underline" title="{{ $user->name }}">{{ $user->name }}</a>: {{ $user->winsToday() }} wins
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
                            <li class="text-gray-800 flex items-center">
                                <a href="{{ route('users.show', $user) }}" class="max-w-xs truncate block text-blue-700 hover:underline" title="{{ $user->name }}">{{ $user->name }}</a>: {{ $user->winsThisWeek() }} wins
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
                            <li class="text-gray-800 flex items-center">
                                <a href="{{ route('users.show', $user) }}" class="max-w-xs truncate block text-blue-700 hover:underline" title="{{ $user->name }}">{{ $user->name }}</a>: {{ $user->winsAllTime() }} wins
                            </li>
                        @empty
                            <li class="text-gray-500">No data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('register') }}" class="mr-4 text-blue-600 hover:underline">Registreren</a>
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Inloggen</a>
        </div>
    </div>
</div>
@endsection
