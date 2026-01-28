@extends('layouts.app')

@section('title', 'Nouvelle Réservation')

@section('content')
<div class="main-container">
    <div class="card card-vibrant" style="max-width: 800px; margin: auto;">
        <div class="card-header bg-vibrant">
            <h2 class="card-title">Effectuer une demande de réservation</h2>
        </div>

        <div class="card-body">
            @if ($errors->any())
            <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="id_ressource" class="form-label">Ressource souhaitée</label>
                    <select name="id_ressource" id="id_ressource" class="form-control" required>
                        <option value="">Sélectionnez une ressource...</option>
                        @foreach($ressources as $ressource)
                        <option value="{{ $ressource->id_ressource }}"
                            {{ isset($selectedRessource) && $selectedRessource->id_ressource == $ressource->id_ressource ? 'selected' : '' }}>
                            {{ $ressource->nom }} ({{ $ressource->categorie->nom }}) - {{ $ressource->cpu }} / {{ $ressource->ram }}
                        </option>

                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2" style="gap: 1rem;">
                    <div class="form-group">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="justification" class="form-label">Justification de la demande</label>
                    <textarea name="justification" id="justification" rows="4" class="form-control" required placeholder="Veuillez expliquer pourquoi vous avez besoin de cette ressource...">{{ old('justification') }}</textarea>
                </div>

                <div class="form-group" style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection