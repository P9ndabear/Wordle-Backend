@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Reactiesysteem</h2>
    <!-- Nieuwe reactie toevoegen -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-2">Plaats een reactie</h3>
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Reactie op</label>
                <select class="border rounded px-3 py-2 w-full">
                    <option>(profiel of spel selecteren)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jouw reactie</label>
                <textarea class="border rounded px-3 py-2 w-full" rows="3" placeholder="Typ je reactie..."></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Plaatsen</button>
        </form>
    </div>
    <!-- Reacties lijst -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Reacties</h3>
        <ul class="space-y-4">
            <li class="border-b pb-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-semibold">(auteur)</span>
                    <span class="text-xs text-gray-400">(datum/tijd)</span>
                </div>
                <div class="text-gray-700">(inhoud van de reactie)</div>
                <div class="text-xs text-gray-500 mt-1">Op: (profiel of spel)</div>
            </li>
            <!-- Meer reacties als placeholder -->
        </ul>
    </div>
</div>
@endsection 