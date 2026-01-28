@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Modifier l'Utilisateur: {{ $user->prenom }} {{ $user->nom }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection