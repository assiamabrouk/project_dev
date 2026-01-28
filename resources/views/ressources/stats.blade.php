@extends('layouts.app')

@section('title', 'Statistiques de la ressource')

@section('content')
<div class="main-container">
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">Statistiques de la ressource</h1>
            <p class="page-subtitle">Vue synthétique de l’utilisation de {{ $ressource->nom }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('ressources.show', $ressource->id_ressource) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Retour à la fiche
            </a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <div style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="font-size:0.85rem;color:#6b7280;margin-bottom:0.25rem;">Réservations totales</div>
            <div style="font-size:2rem;font-weight:700;color:#111827;">{{ $stats['total_reservations'] }}</div>
        </div>

        <div style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="font-size:0.85rem;color:#6b7280;margin-bottom:0.25rem;">Réservations actives</div>
            <div style="font-size:2rem;font-weight:700;color:#16a34a;">{{ $stats['reservations_actives'] }}</div>
        </div>

        <div style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="font-size:0.85rem;color:#6b7280;margin-bottom:0.25rem;">Réservations terminées</div>
            <div style="font-size:2rem;font-weight:700;color:#2563eb;">{{ $stats['reservations_terminees'] }}</div>
        </div>

        <div style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="font-size:0.85rem;color:#6b7280;margin-bottom:0.25rem;">Maintenances</div>
            <div style="font-size:2rem;font-weight:700;color:#b91c1c;">{{ $stats['maintenances_count'] }}</div>
        </div>
    </div>
</div>
@endsection

