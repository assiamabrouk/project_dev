@extends('layouts.app')

@section('title', 'Statistiques globales')

@section('content')
<div class="main-container">
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">Statistiques Globales</h1>
            <p class="page-subtitle">Vue d’ensemble de l’activité du Data Center</p>
        </div>
    </div>

    <div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <div class="stat-card" style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6b7280;">Ressources</div>
                    <div style="font-size:2rem;font-weight:700;color:#111827;">{{ $totalRessources }}</div>
                </div>
                <div style="background:#eef2ff;color:#4f46e5;border-radius:999px;padding:0.75rem;">
                    <i class="fas fa-server"></i>
                </div>
            </div>
        </div>

        <div class="stat-card" style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6b7280;">Utilisateurs</div>
                    <div style="font-size:2rem;font-weight:700;color:#111827;">{{ $totalUsers }}</div>
                </div>
                <div style="background:#fef3c7;color:#d97706;border-radius:999px;padding:0.75rem;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="stat-card" style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6b7280;">Catégories</div>
                    <div style="font-size:2rem;font-weight:700;color:#111827;">{{ $totalCategories }}</div>
                </div>
                <div style="background:#ecfdf3;color:#16a34a;border-radius:999px;padding:0.75rem;">
                    <i class="fas fa-layer-group"></i>
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
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:2rem;">
        <div class="card" style="background:#fff;border-radius:0.75rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.05rem;font-weight:600;">Réservations par statut</h2>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                @php $total = array_sum($reservationsByStatus); @endphp
                <div style="display:grid;gap:0.75rem;">
                    @foreach($reservationsByStatus as $statut => $count)
                        @php $percent = $total > 0 ? round($count * 100 / $total, 1) : 0; @endphp
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="min-width:110px;font-size:0.85rem;color:#4b5563;text-transform:capitalize;">
                                {{ str_replace('_',' ',$statut) }}
                            </div>
                            <div style="flex:1;height:8px;border-radius:999px;background:#e5e7eb;overflow:hidden;">
                                <div style="height:100%;width:{{ $percent }}%;background:#2563eb;border-radius:999px;"></div>
                            </div>
                            <div style="min-width:70px;text-align:right;font-size:0.85rem;color:#6b7280;">
                                {{ $count }} ({{ $percent }}%)
                            </div>
                        </div>
                    @endforeach
                    @if(empty($reservationsByStatus))
                        <div style="font-size:0.9rem;color:#6b7280;">Aucune réservation enregistrée.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card" style="background:#fff;border-radius:0.75rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.05rem;font-weight:600;">Ressources par catégorie</h2>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                @php $totalCat = array_sum($ressourcesByCategory); @endphp
                <div style="display:grid;gap:0.75rem;">
                    @foreach($ressourcesByCategory as $nom => $count)
                        @php $percent = $totalCat > 0 ? round($count * 100 / $totalCat, 1) : 0; @endphp
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="min-width:140px;font-size:0.85rem;color:#4b5563;">{{ $nom }}</div>
                            <div style="flex:1;height:8px;border-radius:999px;background:#e5e7eb;overflow:hidden;">
                                <div style="height:100%;width:{{ $percent }}%;background:#16a34a;border-radius:999px;"></div>
                            </div>
                            <div style="min-width:70px;text-align:right;font-size:0.85rem;color:#6b7280;">
                                {{ $count }} ({{ $percent }}%)
                            </div>
                        </div>
                    @endforeach
                    @if(empty($ressourcesByCategory))
                        <div style="font-size:0.9rem;color:#6b7280;">Aucune ressource définie.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:2rem;margin-top:2rem;">
        <div class="card" style="background:#fff;border-radius:0.75rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.05rem;font-weight:600;">Utilisateurs par type</h2>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                @php $totalUsersType = array_sum($usersByType); @endphp
                <div style="display:grid;gap:0.75rem;">
                    @foreach($usersByType as $type => $count)
                        @php $percent = $totalUsersType > 0 ? round($count * 100 / $totalUsersType, 1) : 0; @endphp
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="min-width:120px;font-size:0.85rem;color:#4b5563;text-transform:capitalize;">
                                {{ $type }}
                            </div>
                            <div style="flex:1;height:8px;border-radius:999px;background:#e5e7eb;overflow:hidden;">
                                <div style="height:100%;width:{{ $percent }}%;background:#fbbf24;border-radius:999px;"></div>
                            </div>
                            <div style="min-width:70px;text-align:right;font-size:0.85rem;color:#6b7280;">
                                {{ $count }} ({{ $percent }}%)
                            </div>
                        </div>
                    @endforeach
                    @if(empty($usersByType))
                        <div style="font-size:0.9rem;color:#6b7280;">Aucun utilisateur enregistré.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card" style="background:#fff;border-radius:0.75rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.05rem;font-weight:600;">Évolution des réservations</h2>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                <div id="admin-reservations-chart" style="width:100%;height:240px;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var data = @json($reservationsPerMonth);
        var container = document.getElementById('admin-reservations-chart');
        if (!container) return;
        if (!Array.isArray(data) || data.length === 0) {
            container.textContent = 'Aucune donnée de réservation pour la période sélectionnée.';
            container.style.fontSize = '0.9rem';
            container.style.color = '#6b7280';
            return;
        }
        var max = 0;
        data.forEach(function (item) {
            if (item.count > max) max = item.count;
        });
        if (max === 0) max = 1;
        var wrapper = document.createElement('div');
        wrapper.style.display = 'flex';
        wrapper.style.alignItems = 'flex-end';
        wrapper.style.gap = '8px';
        wrapper.style.height = '180px';
        data.forEach(function (item) {
            var col = document.createElement('div');
            col.style.flex = '1';
            col.style.display = 'flex';
            col.style.flexDirection = 'column';
            col.style.alignItems = 'center';
            var bar = document.createElement('div');
            bar.style.width = '22px';
            bar.style.borderRadius = '999px 999px 0 0';
            bar.style.background = '#0ea5e9';
            bar.style.height = (item.count / max * 100) + '%';
            var label = document.createElement('div');
            label.style.marginTop = '0.35rem';
            label.style.fontSize = '0.75rem';
            label.style.color = '#6b7280';
            label.textContent = item.month + '/' + String(item.year).slice(-2);
            col.appendChild(bar);
            col.appendChild(label);
            wrapper.appendChild(col);
        });
        container.appendChild(wrapper);
    });
</script>
@endpush

@endsection

