@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Détails de la réservation #{{ $reservation->id_reservation }}</h2>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary" style="background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Informations Réservation</h3>
                <p style="margin-bottom: 0.5rem;"><strong>Statut:</strong> 
                    @if($reservation->statut == 'en_attente')
                        <span class="nav-badge" style="background-color: #eab308; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">En attente</span>
                    @elseif($reservation->statut == 'approuvee')
                        <span class="nav-badge" style="background-color: #22c55e; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Approuvée</span>
                    @elseif($reservation->statut == 'refusee')
                        <span class="nav-badge" style="background-color: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">Refusée</span>
                    @else
                        <span class="nav-badge" style="background-color: #6b7280; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">{{ ucfirst($reservation->statut) }}</span>
                    @endif
                </p>
                <p style="margin-bottom: 0.5rem;"><strong>Date Début:</strong> {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Date Fin:</strong> {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Date Demande:</strong> {{ \Carbon\Carbon::parse($reservation->date_creation)->format('d/m/Y H:i') }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Justification:</strong></p>
                <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem;">
                    {{ $reservation->justification }}
                </div>
            </div>

            <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Ressource Concernée</h3>
                <p style="margin-bottom: 0.5rem;"><strong>Nom:</strong> {{ $reservation->ressource->nom }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Catégorie:</strong> {{ $reservation->ressource->categorie->nom ?? 'N/A' }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Description:</strong> {{ $reservation->ressource->description }}</p>
                
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; margin-top: 1.5rem;">Demandeur</h3>
                <p style="margin-bottom: 0.5rem;"><strong>Nom:</strong> {{ $reservation->utilisateur->nom }} {{ $reservation->utilisateur->prenom }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Email:</strong> {{ $reservation->utilisateur->email }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Type:</strong> {{ $reservation->utilisateur->user_type }}</p>
            </div>
        </div>

        @if($reservation->decision)
            <div style="margin-top: 2rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Décision</h3>
                <p style="margin-bottom: 0.5rem;"><strong>Décision:</strong> {{ ucfirst($reservation->decision->decision) }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Par:</strong> {{ $reservation->decision->utilisateur->nom ?? 'Inconnu' }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($reservation->decision->date_decision)->format('d/m/Y H:i') }}</p>
                @if($reservation->decision->commentaire)
                    <p style="margin-bottom: 0.5rem;"><strong>Commentaire:</strong></p>
                    <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem;">
                        {{ $reservation->decision->commentaire }}
                    </div>
                @endif
            </div>
        @endif

        @php
            $user = Auth::user();
            $canDecide = false;
            if ($reservation->statut === 'en_attente') {
                if ($user->role === 'admin') {
                    $canDecide = true;
                } elseif ($user->role === 'responsable') {
                    $categories = $user->categorieRessources->pluck('id_categorie')->toArray();
                    if (in_array($reservation->ressource->id_categorie, $categories)) {
                        $canDecide = true;
                    }
                }
            }
        @endphp

        @if($canDecide)
            <div style="margin-top: 2rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Traitement de la demande</h3>
                <form method="POST" action="{{ route('reservations.approve', $reservation->id_reservation) }}" id="decisionForm">
                    @csrf
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="commentaire" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Commentaire (Requis pour refus)</label>
                        <textarea name="commentaire" id="commentaire" class="form-control" rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn" style="background-color: #22c55e; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer;">
                            <i class="fas fa-check"></i> Approuver
                        </button>
                        
                        <button type="submit" formaction="{{ route('reservations.reject', $reservation->id_reservation) }}" class="btn" style="background-color: #ef4444; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer;">
                            <i class="fas fa-times"></i> Refuser
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection