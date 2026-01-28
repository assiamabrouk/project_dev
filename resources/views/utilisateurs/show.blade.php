@extends('layouts.app')

@section('title', 'Détails Utilisateur')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <!-- Carte profil -->
            <div class="card">
                <div class="card-body text-center">
                    @if($user->img)
                        <img src="{{ asset('storage/' . $user->img) }}" 
                             class="rounded-circle mb-3" 
                             width="150" height="150">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 150px; height: 150px; font-size: 3rem;">
                            {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                        </div>
                    @endif
                    
                    <h4>{{ $user->prenom }} {{ $user->nom }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="mb-2">
                        <span class="badge bg-{{ $user->statut == 'actif' ? 'success' : 'secondary' }}">
                            {{ ucfirst($user->statut) }}
                        </span>
                        <span class="badge bg-primary ms-1">{{ ucfirst($user->role) }}</span>
                        <span class="badge bg-info ms-1">{{ ucfirst($user->user_type) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Informations détaillées -->
            <div class="card">
                <div class="card-header">
                    <h5>Informations Détailées</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Téléphone:</th>
                            <td>{{ $user->telephone ?? 'Non renseigné' }}</td>
                        </tr>
                        <tr>
                            <th>Date de création:</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Dernière mise à jour:</th>
                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Réservations récentes -->
            @if($user->reservations->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Réservations Récentes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ressource</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->ressource->nom ?? 'N/A' }}</td>
                                    <td>{{ $reservation->date_debut->format('d/m/Y') }}</td>
                                    <td>{{ $reservation->date_fin->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reservation->statut == 'approuvee' ? 'success' : 'warning' }}">
                                            {{ $reservation->statut }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection