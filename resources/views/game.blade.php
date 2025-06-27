@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-200">
<div class="max-w-xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold mb-10 text-gray-800 text-center">Wordle</h2>
    <!-- Spelstatus verwijderd -->

    @if($gameData->status === 'pending')
        @if(isset($canAccept) && $canAccept)
            <form method="POST" action="{{ route('game.accept', $gameData->id) }}">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Accepteer spel</button>
            </form>
        @else
            <div class="text-gray-600 mb-8">Wacht tot je tegenstander het spel accepteert.</div>
            <script>
            function pollGameStatus() {
                fetch('/api/game-status/{{ $gameData->id }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'active' && '{{ $gameData->status }}' === 'pending') {
                            location.reload();
                        }
                        if (data.status === 'finished') {
                            location.reload();
                        }
                    });
            }
            setInterval(pollGameStatus, 3000);
            </script>
        @endif
    @elseif($gameData->status === 'active')
        <!-- Beurt-indicator -->
        <div class="text-center mb-4">
            @if($gameData->current_turn_user_id === Auth::id())
                <p class="text-green-600 font-bold">Jouw beurt!</p>
            @else
                <p class="text-gray-600">Wacht op de beurt van je tegenstander...</p>
            @endif
        </div>
        <!-- Wordle bord -->
        <div class="flex flex-col items-center space-y-2 mb-8 mt-8">
            @php
                $firstEmptyIdx = null;
                foreach($rows as $idx => $row) {
                    if(collect($row)->every(function($cell) { return empty($cell['letter']); })) {
                        $firstEmptyIdx = $idx;
                        break;
                    }
                }
            @endphp
            @foreach($rows as $rowIdx => $row)
                @if($rowIdx === $firstEmptyIdx && $gameData->current_turn_user_id === Auth::id())
                    <!-- Actieve rij: inputvelden in een form -->
                    <form id="guess-form" class="flex space-x-2 w-full mb-2 justify-center" method="POST" action="{{ route('game.attempt', $gameData->id) }}">
                        @csrf
                        @for($i = 0; $i < 5; $i++)
                            <input type="text" maxlength="1" name="letters[]" class="border rounded w-20 h-20 text-center text-3xl font-bold font-mono bg-white focus:bg-blue-100" style="border-width: 4px; border-color: black;" autocomplete="off" inputmode="text" pattern="[A-Za-z]" required />
                        @endfor
                    </form>
                @else
                    <div class="flex space-x-2 mb-2">
                        @foreach($row as $cell)
                            <div class="w-20 h-20 flex items-center justify-center text-3xl font-bold rounded border-4 border-black"
                                style="background: {{ $cell['color'] === 'green' ? '#22c55e' : ($cell['color'] === 'yellow' ? '#facc15' : '#a3a3a3') }}; color: black;"
                            >
                                {{ $cell['letter'] ?: '-' }}
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
        <!-- Indienen knop direct onder het bord, boven Spel verlaten -->
        @if($gameData->current_turn_user_id === Auth::id())
            <div class="flex flex-col items-center mb-2">
                <button form="guess-form" type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700 mb-2">Indienen</button>
            </div>
        @endif
        <!-- Spel verlaten knop -->
        <div class="flex flex-col items-center">
            <form method="POST" action="{{ route('game.forfeit', $gameData->id) }}">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Spel verlaten</button>
            </form>
        </div>
        <script>
        // Automatisch naar volgende input springen
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('#guess-form input[type="text"]');
            inputs.forEach((input, idx) => {
                input.addEventListener('input', function(e) {
                    if (this.value.length === 1 && idx < inputs.length - 1) {
                        inputs[idx + 1].focus();
                    }
                });
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && idx > 0) {
                        inputs[idx - 1].focus();
                    }
                });
            });
            inputs[0].focus();
            // Verzamel letters tot 1 string bij submit
            document.getElementById('guess-form').addEventListener('submit', function(e) {
                let guess = '';
                inputs.forEach(inp => guess += inp.value);
                // Voeg een hidden input toe met de volledige gok
                let hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'guess';
                hidden.value = guess;
                this.appendChild(hidden);
            });
        });
        </script>
        <script>
        function pollGameStatus() {
            fetch('/api/game-status/{{ $gameData->id }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'finished') {
                        location.reload();
                    }
                });
        }
        setInterval(pollGameStatus, 3000);
        </script>
        <script>
        // Refresh page every 5 seconds to check for opponent's turn/moves
        setInterval(function() {
            location.reload();
        }, 5000);
        </script>
    @else
        <div class="text-gray-600 mb-8">Dit spel is afgelopen.</div>
    @endif
</div>
</div>
@endsection 