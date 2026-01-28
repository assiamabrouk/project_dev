@extends('layouts.app')

@section('title', 'Mes Réservations')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Mes Réservations</h2>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Réservation
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Statut</th>
                        <th>Date de demande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->ressource->nom }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
                        <td>
                            @if($reservation->statut == 'en_attente')
                                <span class="nav-badge" style="background-color: var(--warning-color);">En attente</span>
                            @elseif($reservation->statut == 'active')
                                <span class="nav-badge" style="background-color: var(--success-color);">Active</span>
                            @elseif($reservation->statut == 'approuvee')
                                <span class="nav-badge" style="background-color: var(--info-color);">Approuvée</span>
                            @elseif($reservation->statut == 'refusee')
                                <span class="nav-badge" style="background-color: var(--danger-color);">Refusée</span>
                            @else
                                <span class="nav-badge" style="background-color: var(--secondary-color);">{{ ucfirst($reservation->statut) }}</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date_creation)->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('reservations.show', $reservation->id_reservation) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($reservation->statut == 'en_attente' && auth()->id() == $reservation->user_id)
                                <a href="{{ route('reservations.edit', $reservation->id_reservation) }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('reservations.destroy', $reservation->id_reservation) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune réservation trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
