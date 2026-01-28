@extends('layouts.app')

@section('title', 'Statistiques Responsable')

@section('content')
<div class="main-container">
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">Statistiques de vos ressources</h1>
            <p class="page-subtitle">Suivi de l’occupation et des demandes sur les ressources que vous gérez</p>
        </div>
    </div>

    <div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <div class="stat-card" style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6b7280;">Ressources gérées</div>
                    <div style="font-size:2rem;font-weight:700;color:#111827;">{{ $managedRessources }}</div>
                </div>
                <div style="background:#eef2ff;color:#4f46e5;border-radius:999px;padding:0.75rem;">
                    <i class="fas fa-network-wired"></i>
                </div>
            </div>
        </div>

        <div class="stat-card" style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6b7280;">Taux d’occupation</div>
                    <div style="font-size:2rem;font-weight:700;color:#111827;">{{ $occupationRate }}%</div>
                </div>
                <div style="background:#fee2e2;color:#b91c1c;border-radius:999px;padding:0.75rem;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background:#fff;border-radius:0.75rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);overflow:hidden;">
        <div class="card-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid #e5e7eb;">
            <h2 style="font-size:1.05rem;font-weight:600;">Demandes par statut</h2>
        </div>
        <div class="card-body" style="padding:1.5rem;">
            @php $total = array_sum($demandesByStatus); @endphp
            <div style="display:grid;gap:0.75rem;">
                @foreach($demandesByStatus as $statut => $count)
                    @php $percent = $total > 0 ? round($count * 100 / $total, 1) : 0; @endphp
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="min-width:120px;font-size:0.85rem;color:#4b5563;text-transform:capitalize;">
                            {{ str_replace('_',' ',$statut) }}
                        </div>
                        <div style="flex:1;height:8px;border-radius:999px;background:#e5e7eb;overflow:hidden;">
                            <div style="height:100%;width:{{ $percent }}%;background:#0ea5e9;border-radius:999px;"></div>
                        </div>
                        <div style="min-width:70px;text-align:right;font-size:0.85rem;color:#6b7280;">
                            {{ $count }} ({{ $percent }}%)
                        </div>
                    </div>
                @endforeach
                @if(empty($demandesByStatus))
                    <div style="font-size:0.9rem;color:#6b7280;">Aucune demande pour vos ressources.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

