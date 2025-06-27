@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Profiel & Voorkeuren</h2>
    <!-- Profielinformatie -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Profielinformatie</h3>
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Naam</label>
                <input type="text" class="border rounded px-3 py-2 w-full" value="(placeholder naam)">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" class="border rounded px-3 py-2 w-full" value="(placeholder email)">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Avatar URL</label>
                <input type="text" class="border rounded px-3 py-2 w-full" value="(placeholder avatar url)">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Opslaan</button>
        </form>
    </div>
    <!-- Voorkeuren -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Voorkeuren</h3>
        <ul class="mb-4 space-y-2">
            <li class="flex justify-between items-center bg-gray-50 border border-gray-200 rounded px-4 py-2">
                <span>(key)</span>
                <span>(value)</span>
            </li>
        </ul>
        <form class="flex space-x-2">
            <input type="text" class="border rounded px-3 py-2 w-32" placeholder="Sleutel">
            <input type="text" class="border rounded px-3 py-2 w-32" placeholder="Waarde">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Toevoegen</button>
        </form>
    </div>
    <!-- Wachtwoord wijzigen -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Wachtwoord wijzigen</h3>
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nieuw wachtwoord</label>
                <input type="password" class="border rounded px-3 py-2 w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Bevestig wachtwoord</label>
                <input type="password" class="border rounded px-3 py-2 w-full">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Wijzigen</button>
        </form>
    </div>
    <!-- Voeg dit toe waar je de knop wilt -->
    <button id="random-opponent-btn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tegen willekeurige tegenstander</button>

    <div id="waiting-message" style="display:none;">
        Wachten op tegenstander...
    </div>

    <script>
    document.getElementById('random-opponent-btn').addEventListener('click', function() {
        fetch('/matchmaking/join', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.matched) {
                window.location.href = '/game/' + data.game_id;
            } else {
                document.getElementById('random-opponent-btn').style.display = 'none';
                document.getElementById('waiting-message').style.display = 'block';
                pollForMatch();
            }
        });
    });

    function pollForMatch() {
        setTimeout(function() {
            fetch('/matchmaking/check')
            .then(response => response.json())
            .then(data => {
                if (data.matched) {
                    window.location.href = '/game/' + data.game_id;
                } else {
                    pollForMatch();
                }
            });
        }, 2000); // elke 2 seconden checken
    }
    </script>
</div>
@endsection 