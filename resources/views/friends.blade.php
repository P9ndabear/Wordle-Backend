@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Vriendenbeheer</h2>
    <!-- Vriend toevoegen -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-2">Vriend toevoegen</h3>
        <form method="POST" action="{{ route('friends.invite') }}" class="flex space-x-2">
            @csrf
            <input type="text" name="identifier" class="border rounded px-3 py-2 w-64" placeholder="Gebruikersnaam of e-mail">
            <button type="submit" class="bg-blue-200 text-black px-4 py-2 rounded hover:bg-blue-300">Uitnodigen</button>
        </form>
    </div>
    <!-- Verstuurde uitnodigingen -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-2">Verstuurde uitnodigingen</h3>
        <ul class="space-y-2">
            @forelse($sentRequests as $request)
                <li class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded px-4 py-2">
                    <a href="{{ route('users.show', $request->friend) }}" class="text-blue-700 hover:underline">{{ $request->friend->name }}</a>
                    <span class="text-xs text-gray-500">Status: 
                        <span class="font-bold">
                            @if($request->status === 'pending')
                                In afwachting
                            @elseif($request->status === 'accepted')
                                Geaccepteerd
                            @elseif($request->status === 'blocked')
                                Geweigerd
                            @endif
                        </span>
                    </span>
                </li>
            @empty
                <li class="text-gray-500">Geen verstuurde uitnodigingen</li>
            @endforelse
        </ul>
    </div>
    <!-- Openstaande uitnodigingen -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-2">Openstaande uitnodigingen</h3>
        <ul class="space-y-2">
            @forelse($invitations as $invitation)
                <li class="flex items-center justify-between bg-yellow-50 border border-yellow-200 rounded px-4 py-2">
                    <span>{{ $invitation->user->name }}</span>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('friends.accept', $invitation) }}">
                            @csrf
                            <button type="submit" class="bg-green-200 text-black px-3 py-1 rounded hover:bg-green-300">Accepteren</button>
                        </form>
                        <form method="POST" action="{{ route('friends.reject', $invitation) }}">
                            @csrf
                            <button type="submit" class="bg-red-200 text-black px-3 py-1 rounded hover:bg-red-300">Weigeren</button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="text-gray-500">Geen openstaande uitnodigingen</li>
            @endforelse
        </ul>
    </div>
    <!-- Vriendenlijst -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-2">Vrienden</h3>
        <ul class="space-y-2">
            @forelse($friends as $friend)
                <li class="text-gray-800">
                    <a href="{{ route('users.show', $friend) }}" class="text-blue-700 hover:underline">{{ $friend->name }}</a>
                </li>
            @empty
                <li class="text-gray-500">Je hebt nog geen vrienden</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection 