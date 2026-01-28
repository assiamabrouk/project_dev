@extends('layouts.app')

@section('title', 'Mes statistiques')

@section('content')
<div class="main-container">
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">Mes Statistiques</h1>
            <p class="page-subtitle">Suivi de vos réservations dans le Data Center</p>
        </div>
    </div>

    <div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;margin-bottom:2rem;">
        @php $total = array_sum($myReservations); @endphp
        <div class="stat-card" style="background:#fff;border-radius:0.75rem;padding:1.5rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6b7280;">Nombre total de réservations</div>
                    <div style="font-size:2rem;font-weight:700;color:#111827;">{{ $total }}</div>
                </div>
                <div style="background:#eff6ff;color:#2563eb;border-radius:999px;padding:0.75rem;">
                    <i class="fas fa-calendar-check"></i>
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
                <div style="display:grid;gap:0.75rem;">
                    @foreach($myReservations as $statut => $count)
                        @php $percent = $total > 0 ? round($count * 100 / $total, 1) : 0; @endphp
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="min-width:120px;font-size:0.85rem;color:#4b5563;text-transform:capitalize;">
                                {{ str_replace('_',' ',$statut) }}
                            </div>
                            <div style="flex:1;height:8px;border-radius:999px;background:#e5e7eb;overflow:hidden;">
                                <div style="height:100%;width:{{ $percent }}%;background:#22c55e;border-radius:999px;"></div>
                            </div>
                            <div style="min-width:70px;text-align:right;font-size:0.85rem;color:#6b7280;">
                                {{ $count }} ({{ $percent }}%)
                            </div>
                        </div>
                    @endforeach
                    @if(empty($myReservations))
                        <div style="font-size:0.9rem;color:#6b7280;">Vous n’avez encore effectué aucune réservation.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card" style="background:#fff;border-radius:0.75rem;box-shadow:0 10px 30px rgba(15,23,42,0.06);overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.05rem;font-weight:600;">Historique sur 6 mois</h2>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                <div id="user-reservations-chart" style="width:100%;height:240px;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var data = @json($reservationsPerMonth);
        var container = document.getElementById('user-reservations-chart');
        if (!container) return;
        if (!Array.isArray(data) || data.length === 0) {
            container.textContent = 'Aucune réservation sur la période affichée.';
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
            bar.style.background = '#6366f1';
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

