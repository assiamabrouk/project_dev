@extends('layouts.app')

@section('title', 'Modifier la réservation')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Modifier la réservation #{{ $reservation->id_reservation }}</h2>
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

        <form action="{{ route('reservations.update', $reservation->id_reservation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="id_ressource" class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Ressource souhaitée</label>
                <select name="id_ressource" id="id_ressource" class="form-control" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Sélectionnez une ressource...</option>
                    @foreach($ressources as $ressource)
                        <option value="{{ $ressource->id_ressource }}" {{ old('id_ressource', $reservation->id_ressource) == $ressource->id_ressource ? 'selected' : '' }}>
                            {{ $ressource->nom }} ({{ $ressource->categorie->nom }}) - {{ $ressource->cpu }} / {{ $ressource->ram }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label for="date_debut" class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Date de début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut', $reservation->date_debut) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div class="form-group">
                    <label for="date_fin" class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin', $reservation->date_fin) }}" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="justification" class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Justification de la demande</label>
                <textarea name="justification" id="justification" rows="4" class="form-control" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ old('justification', $reservation->justification) }}</textarea>
            </div>

            <div class="form-group" style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <a href="{{ route('reservations.index') }}" class="btn btn-secondary" style="background-color: #6b7280; color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem;">Annuler</a>
                <button type="submit" class="btn btn-primary" style="background-color: #2563eb; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection