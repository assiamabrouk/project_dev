@extends('layouts.app')

@section('title', 'Modifier le Profil')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Modifier mon profil</h1>

    {{-- Message de succès --}}
    @if(session('status') === 'profile-updated')
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            Profil mis à jour avec succès !
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        {{-- Image de profil --}}
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                @if($user->img)
                    <img class="h-20 w-20 object-cover rounded-full" src="{{ asset('storage/' . $user->img) }}" alt="Photo de profil">
                @else
                    <div class="h-20 w-20 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="text-gray-500 font-bold">A</span>
                    </div>
                @endif
            </div>
            <div>
                <label class="block mb-1 font-semibold" for="img">Changer la photo</label>
                <input type="file" name="img" id="img" class="border rounded px-2 py-1 w-full">
                @error('img')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Nom complet --}}
        <div>
            <label class="block mb-1 font-semibold" for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $user->nom) }}" class="border rounded px-3 py-2 w-full">
            @error('nom')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-semibold" for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $user->prenom) }}" class="border rounded px-3 py-2 w-full">
            @error('prenom')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block mb-1 font-semibold" for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="border rounded px-3 py-2 w-full">
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Téléphone --}}
        <div>
            <label class="block mb-1 font-semibold" for="telephone">Téléphone</label>
            <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $user->telephone) }}" class="border rounded px-3 py-2 w-full">
            @error('telephone')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div>
            <label class="block mb-1 font-semibold" for="password">Nouveau mot de passe</label>
            <input type="password" name="password" id="password" class="border rounded px-3 py-2 w-full">
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-semibold" for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="border rounded px-3 py-2 w-full">
        </div>

        {{-- Boutons --}}
        <div class="flex justify-between items-center mt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Mettre à jour
            </button>

            {{-- Suppression du compte --}}
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?')">
                    Supprimer le compte
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
