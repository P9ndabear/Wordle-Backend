@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">{{ $user->name }}'s Profiel</h2>

    <!-- Reacties op profiel -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-2">Reacties op profiel</h3>
        <form method="POST" action="{{ route('comments.store') }}">
            @csrf
            <input type="hidden" name="commentable_id" value="{{ $user->id }}">
            <input type="hidden" name="commentable_type" value="App\Models\User">
            <textarea name="content" class="w-full border rounded p-2" required></textarea>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Plaatsen</button>
        </form>
        <ul class="mt-4">
            @forelse($comments as $comment)
                <li class="mb-4 border-b pb-2">
                    <div class="text-sm text-gray-700">
                        <strong>{{ $comment->user->name }}</strong>
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-gray-900">{{ $comment->content }}</div>
                </li>
            @empty
                <li class="text-gray-500">Nog geen reacties.</li>
            @endforelse
        </ul>
    </div>

    <!-- Speluitslagen en reacties daarop -->
    <div>
        <h3 class="text-lg font-semibold mb-2">Speluitslagen van {{ $user->name }}</h3>
        @foreach($games as $game)
            <div class="mb-6 border p-4 rounded">
                <div class="mb-2 font-semibold">
                    {{ $user->name }} vs 
                    @if($game->user_id === $user->id)
                        {{ $game->opponent->name ?? 'Onbekend' }}
                    @else
                        {{ $game->user->name ?? 'Onbekend' }}
                    @endif
                </div>
                <div class="mb-2">Status: {{ $game->status }}</div>
                <div class="mb-2">
                    <form method="POST" action="{{ route('comments.store') }}">
                        @csrf
                        <input type="hidden" name="commentable_id" value="{{ $game->id }}">
                        <input type="hidden" name="commentable_type" value="App\Models\Game">
                        <textarea name="content" class="w-full border rounded p-2" required placeholder="Reageer op deze game"></textarea>
                        <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Plaatsen</button>
                    </form>
                </div>
                <ul>
                    @forelse($game->comments as $comment)
                        <li class="mb-2 border-b pb-1">
                            <div class="text-sm text-gray-700">
                                <strong>{{ $comment->user->name }}</strong>
                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-gray-900">{{ $comment->content }}</div>
                        </li>
                    @empty
                        <li class="text-gray-500">Nog geen reacties op deze game.</li>
                    @endforelse
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endsection 