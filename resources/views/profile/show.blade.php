@extends('layouts.app')

@section('title', 'Détails du Profil')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Détails du Profil</h1>

    <div class="border rounded p-6 flex flex-col md:flex-row md:space-x-6">
        {{-- Photo de profil --}}
        <div class="w-full md:w-1/4 mb-4 md:mb-0 flex flex-col items-center">
            @if($user->img)
                <img src="{{ asset('storage/' . $user->img) }}" alt="Photo de profil" class="w-32 h-32 rounded-full mb-4 object-cover">
            @else
                <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                    <span class="text-gray-500 text-xl font-bold">{{ strtoupper(substr($user->nom,0,1)) }}</span>
                </div>
            @endif
            <h2 class="font-semibold text-lg">{{ $user->nom }} {{ $user->prenom }}</h2>
            <p class="text-gray-600 capitalize">{{ $user->role }}</p>
        </div>

        {{-- Informations détaillées --}}
        <div class="w-full md:w-3/4 space-y-3">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Téléphone:</strong> {{ $user->telephone ?? 'Non défini' }}</p>
            <p><strong>Type d'utilisateur:</strong> {{ $user->user_type ?? 'Non défini' }}</p>
            <p><strong>Statut:</strong> {{ ucfirst($user->statut) }}</p>
            <p><strong>Créé le:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Dernière mise à jour:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Modifier mon profil
        </a>
    </div>
</div>
@endsection
