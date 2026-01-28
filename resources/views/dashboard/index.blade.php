@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <div class="main-container">
        <!-- ==================== STATISTIQUES GLOBALES ==================== -->
        <div class="stats-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Ressources Totales -->
            <div class="stat-card"
                style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Ressources Totales</p>
                        <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $totalRessources }}</h3>
                    </div>
                    <div style="background-color: #dbeafe; padding: 0.75rem; border-radius: 0.5rem; color: #2563eb;">
                        <i class="fas fa-server fa-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Utilisateurs -->
            <div class="stat-card"
                style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Utilisateurs</p>
                        <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $totalUsers }}</h3>
                    </div>
                    <div style="background-color: #fce7f3; padding: 0.75rem; border-radius: 0.5rem; color: #db2777;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Réservations Actives -->
            <div class="stat-card"
                style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Réservations Actives</p>
                        <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $activeReservations }}</h3>
                    </div>
                    <div style="background-color: #dcfce7; padding: 0.75rem; border-radius: 0.5rem; color: #16a34a;">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Taux d'occupation -->
            <div class="stat-card"
                style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Taux d'occupation</p>
                        <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $occupationRate }}%</h3>
                    </div>
                    <div style="background-color: #ffedd5; padding: 0.75rem; border-radius: 0.5rem; color: #ea580c;">
                        <i class="fas fa-chart-pie fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STATISTIQUES SUPPLÉMENTAIRES ==================== -->
        <div class="stats-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Catégories -->
            @if(in_array($currentUserRole, ['admin', 'responsable']))
                <div class="stat-card"
                    style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Catégories</p>
                            <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $totalCategories }}</h3>
                        </div>
                        <div style="background-color: #f0f9ff; padding: 0.75rem; border-radius: 0.5rem; color: #0ea5e9;">
                            <i class="fas fa-tags fa-lg"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Réservations en Attente -->
            <div class="stat-card"
                style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Réservations en Attente</p>
                        <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $pendingReservations }}</h3>
                    </div>
                    <div style="background-color: #fef3c7; padding: 0.75rem; border-radius: 0.5rem; color: #d97706;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Réservations Terminées -->
            <div class="stat-card"
                style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Réservations Terminées</p>
                        <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">{{ $completedReservations }}</h3>
                    </div>
                    <div style="background-color: #f0fdf4; padding: 0.75rem; border-radius: 0.5rem; color: #22c55e;">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Statistiques Utilisateurs (Admin seulement) -->
            @if($currentUserRole === 'admin')
                <div class="stat-card"
                    style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Utilisateurs Actifs</p>
                            <h3 style="font-size: 1.875rem; font-weight: 600; color: #111827;">
                                {{ $usersData['activeUsers'] ?? 0 }}</h3>
                        </div>
                        <div style="background-color: #fef2f2; padding: 0.75rem; border-radius: 0.5rem; color: #ef4444;">
                            <i class="fas fa-user-check fa-lg"></i>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- ==================== CONTENU PRINCIPAL ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">

            <!-- Réservations Récentes -->
            <div class="card"
                style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <div class="card-header"
                    style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="card-title" style="font-size: 1.125rem; font-weight: 600;">
                        @if($currentUserRole === 'admin')
                            Toutes les Réservations Récentes
                        @elseif($currentUserRole === 'responsable')
                            Réservations de ma Catégorie
                        @else
                            Mes Réservations Récentes
                        @endif
                    </h3>
                    <a href="{{ route('reservations.index') }}"
                        style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">Voir tout</a>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background-color: #f9fafb;">
                            <tr>
                                <th
                                    style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6b7280;">
                                    Ressource</th>
                                @if(in_array($currentUserRole, ['admin', 'responsable']))
                                    <th
                                        style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6b7280;">
                                        Utilisateur</th>
                                @endif
                                <th
                                    style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6b7280;">
                                    Statut</th>
                                <th
                                    style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #6b7280;">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReservations as $reservation)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="font-weight: 500; color: #111827;">{{ $reservation->ressource->nom }}</div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">
                                            {{ $reservation->ressource->categorie->nom ?? '' }}</div>
                                    </td>
                                    @if(in_array($currentUserRole, ['admin', 'responsable']))
                                        <td style="padding: 1rem 1.5rem;">
                                            <div style="font-weight: 500; color: #111827;">
                                                {{ $reservation->utilisateur->nom ?? '' }}
                                                {{ $reservation->utilisateur->prenom ?? '' }}
                                            </div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">
                                                {{ $reservation->utilisateur->email ?? '' }}</div>
                                        </td>
                                    @endif
                                    <td style="padding: 1rem 1.5rem;">
                                        @if($reservation->statut == 'en_attente')
                                            <span
                                                style="background-color: #fef3c7; color: #d97706; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">En
                                                attente</span>
                                        @elseif($reservation->statut == 'active')
                                            <span
                                                style="background-color: #dcfce7; color: #16a34a; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Active</span>
                                        @elseif($reservation->statut == 'approuvee')
                                            <span
                                                style="background-color: #dbeafe; color: #2563eb; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Approuvée</span>
                                        @elseif($reservation->statut == 'refusee')
                                            <span
                                                style="background-color: #fee2e2; color: #dc2626; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Refusée</span>
                                        @elseif($reservation->statut == 'termine')
                                            <span
                                                style="background-color: #f3f4f6; color: #6b7280; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Terminée</span>
                                        @else
                                            <span
                                                style="background-color: #f3f4f6; color: #4b5563; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">{{ ucfirst($reservation->statut) }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="font-size: 0.875rem; color: #6b7280;">
                                            {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ in_array($currentUserRole, ['admin', 'responsable']) ? 4 : 3 }}"
                                        style="padding: 1.5rem; text-align: center; color: #6b7280;">
                                        Aucune réservation récente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mon Activité -->
            <div class="card" style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 class="card-title" style="font-size: 1.125rem; font-weight: 600;">Mon Activité</h3>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="background-color: #eff6ff; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 700; color: #2563eb;">{{ $myReservationsCount }}</div>
                            <div style="color: #6b7280; font-size: 0.875rem;">Total Réservations</div>
                        </div>
                        <div style="background-color: #fef3c7; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 700; color: #d97706;">{{ $myPendingReservations }}
                            </div>
                            <div style="color: #6b7280; font-size: 0.875rem;">En attente</div>
                        </div>
                        <div style="background-color: #dbeafe; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 700; color: #1d4ed8;">{{ $myApprovedReservations }}
                            </div>
                            <div style="color: #6b7280; font-size: 0.875rem;">Approuvées</div>
                        </div>
                        <div style="background-color: #dcfce7; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 700; color: #16a34a;">{{ $myActiveReservations }}
                            </div>
                            <div style="color: #6b7280; font-size: 0.875rem;">Actives</div>
                        </div>
                    </div>

                    <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">Actions Rapides</h4>
                    <div style="display: grid; gap: 0.75rem;">
                        <a href="{{ route('reservations.create') }}"
                            style="display: block; padding: 0.75rem; background-color: #eff6ff; color: #1d4ed8; border-radius: 0.375rem; text-decoration: none; text-align: center; font-weight: 500;">
                            <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i> Nouvelle Réservation
                        </a>
                        <a href="{{ route('reservations.index') }}"
                            style="display: block; padding: 0.75rem; background-color: #f3f4f6; color: #374151; border-radius: 0.375rem; text-decoration: none; text-align: center; font-weight: 500;">
                            <i class="fas fa-list" style="margin-right: 0.5rem;"></i> Voir mes réservations
                        </a>

                        <!-- Liens pour responsables et admin -->
                        @if(in_array($currentUserRole, ['admin', 'responsable']))
                            <a href="{{ route('ressources.index') }}"
                                style="display: block; padding: 0.75rem; background-color: #f0f9ff; color: #0ea5e9; border-radius: 0.375rem; text-decoration: none; text-align: center; font-weight: 500;">
                                <i class="fas fa-server" style="margin-right: 0.5rem;"></i> Gérer les Ressources
                            </a>
                        @endif

                        @if($currentUserRole === 'admin')
                            <a href="{{ route('users.index') }}"
                                style="display: block; padding: 0.75rem; background-color: #fef2f2; color: #ef4444; border-radius: 0.375rem; text-decoration: none; text-align: center; font-weight: 500;">
                                <i class="fas fa-users-cog" style="margin-right: 0.5rem;"></i> Gérer les Utilisateurs
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== SECTION HISTORIQUE ==================== -->
        <div class="card"
            style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 2rem;">
            <div class="card-header"
                style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title" style="font-size: 1.125rem; font-weight: 600;">
                    Mon Historique
                    @if(in_array($currentUserRole, ['admin', 'responsable']))
                        et Activités du Système
                    @endif
                </h3>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <!-- Historique des Réservations -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: #4b5563;">
                        <i class="fas fa-history" style="margin-right: 0.5rem;"></i> Historique de mes Réservations
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @forelse($userHistory['reservationHistory'] as $history)
                            <div
                                style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background-color: #f9fafb; border-radius: 0.5rem;">
                                <div
                                    style="flex-shrink: 0; width: 40px; height: 40px; background-color: #e5e7eb; border-radius: 9999px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar" style="color: #4b5563;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 500; color: #111827;">
                                        Réservation #{{ $history->id_reservation }} - {{ $history->ressource->nom }}
                                    </div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">
                                        Statut:
                                        @if($history->statut == 'en_attente')
                                            <span style="color: #d97706;">En attente</span>
                                        @elseif($history->statut == 'approuvee')
                                            <span style="color: #2563eb;">Approuvée</span>
                                        @elseif($history->statut == 'termine')
                                            <span style="color: #16a34a;">Terminée</span>
                                        @else
                                            {{ ucfirst($history->statut) }}
                                        @endif
                                        • Créée le {{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 1rem; color: #9ca3af;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                                Aucun historique de réservation.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Historique des Ressources (pour admin/responsable) -->
                @if(in_array($currentUserRole, ['admin', 'responsable']) && $userHistory['resourceHistory']->count() > 0)
                    <div>
                        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: #4b5563;">
                            <i class="fas fa-server" style="margin-right: 0.5rem;"></i> Historique des Ressources
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @forelse($userHistory['resourceHistory'] as $history)
                                <div
                                    style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background-color: #f0f9ff; border-radius: 0.5rem;">
                                    <div
                                        style="flex-shrink: 0; width: 40px; height: 40px; background-color: #dbeafe; border-radius: 9999px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-cogs" style="color: #2563eb;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 500; color: #111827;">
                                            {{ $history->ressource->nom ?? 'Ressource inconnue' }}
                                        </div>
                                        <div style="font-size: 0.875rem; color: #6b7280;">
                                            État: {{ $history->etat }} •
                                            Du {{ \Carbon\Carbon::parse($history->date_debut_utilisation)->format('d/m/Y') }}
                                            @if($history->date_fin_utilisation)
                                                au {{ \Carbon\Carbon::parse($history->date_fin_utilisation)->format('d/m/Y') }}
                                            @endif
                                        </div>
                                    </div>
                                    <div style="font-size: 0.75rem; color: #9ca3af;">
                                        {{ \Carbon\Carbon::parse($history->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 1rem; color: #9ca3af;">
                                    Aucun historique de ressource.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ==================== GESTION UTILISATEURS (ADMIN SEULEMENT) ==================== -->
        @if($currentUserRole === 'admin' && isset($usersData['allUsers']) && $usersData['allUsers']->count() > 0)
        <div class="card" style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 2rem;">
            <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h3 class="card-title" style="font-size: 1.125rem; font-weight: 600;">
                    <i class="fas fa-users-cog" style="margin-right: 0.5rem;"></i> Gestion des Utilisateurs
                </h3>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <!-- Statistiques utilisateurs -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="background-color: #f0f9ff; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #0ea5e9;">{{ $usersData['allUsers']->count() }}</div>
                        <div style="color: #6b7280; font-size: 0.875rem;">Total Utilisateurs</div>
                    </div>
                    <div style="background-color: #f0fdf4; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #22c55e;">{{ $usersData['activeUsers'] }}</div>
                        <div style="color: #6b7280; font-size: 0.875rem;">Actifs</div>
                    </div>
                    <div style="background-color: #fef2f2; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #ef4444;">{{ $usersData['inactiveUsers'] }}</div>
                        <div style="color: #6b7280; font-size: 0.875rem;">Inactifs</div>
                    </div>
                    <div style="background-color: #fef3c7; padding: 1rem; border-radius: 0.5rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #d97706;">{{ $usersData['supervisors']->count() }}</div>
                        <div style="color: #6b7280; font-size: 0.875rem;">Responsables</div>
                    </div>
                </div>
        
                <!-- Tableau des utilisateurs -->
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background-color: #f9fafb;">
                            <tr>
                                <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280;">Nom</th>
                                <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280;">Email</th>
                                <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280;">Type</th>
                                <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280;">Rôle</th>
                                <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280;">Statut</th>
                                <th style="padding: 0.75rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usersData['allUsers']->take(5) as $userItem)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 1rem 0.75rem;">
                                        <div style="font-weight: 500; color: #111827;">
                                            {{ $userItem->prenom }} {{ $userItem->nom }}
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 0.75rem;">
                                        <div style="color: #6b7280; font-size: 0.875rem;">{{ $userItem->email }}</div>
                                    </td>
                                    <td style="padding: 1rem 0.75rem;">
                                        @if($userItem->user_type == 'ingenieur')
                                            <span style="background-color: #e0e7ff; color: #4f46e5; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Ingénieur</span>
                                        @elseif($userItem->user_type == 'enseignant')
                                            <span style="background-color: #fce7f3; color: #db2777; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Enseignant</span>
                                        @else
                                            <span style="background-color: #fef3c7; color: #d97706; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Doctorant</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 0.75rem;">
                                        @if($userItem->role === 'admin')
                                            <span style="background-color: #fee2e2; color: #dc2626; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Admin</span>
                                        @elseif($userItem->role === 'responsable')
                                            <span style="background-color: #dbeafe; color: #2563eb; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Responsable</span>
                                        @else
                                            <span style="background-color: #dcfce7; color: #16a34a; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Utilisateur</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 0.75rem;">
                                        @if($userItem->statut == 'actif')
                                            <span style="background-color: #dcfce7; color: #16a34a; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Actif</span>
                                        @else
                                            <span style="background-color: #f3f4f6; color: #6b7280; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">Inactif</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 0.75rem;">
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="{{ url('/users/' . $userItem->id . '/edit') }}" 
                                               style="color: #2563eb; text-decoration: none; font-size: 0.875rem;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('users.toggle-status', $userItem->id) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        style="background: none; border: none; color: #d97706; cursor: pointer; padding: 0;">
                                                    @if($userItem->statut == 'actif')
                                                        <i class="fas fa-ban" title="Désactiver"></i>
                                                    @else
                                                        <i class="fas fa-check" title="Activer"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="{{ url('/users') }}" 
                       style="color: #2563eb; text-decoration: none; font-weight: 500;">
                        <i class="fas fa-external-link-alt" style="margin-right: 0.5rem;"></i>
                        Voir tous les utilisateurs
                    </a>
                </div>
            </div>
        </div>
        @endif
        <!-- ==================== GRAPHIQUES ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-top: 2rem;">
            <!-- Graphique des Réservations -->
            <div class="card" style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 class="card-title" style="font-size: 1.125rem; font-weight: 600;">
                        Réservations par Mois ({{ date('Y') }})
                    </h3>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div id="reservations-chart"></div>
                </div>
            </div>

            <!-- Statut des Ressources -->
            <div class="card" style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 class="card-title" style="font-size: 1.125rem; font-weight: 600;">
                        Statut des Ressources
                    </h3>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div id="status-chart"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Graphique des réservations par mois
                var chartData = @json($chartData);
                var months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                var container = document.getElementById('reservations-chart');
                if (container) {
                    var max = Math.max.apply(null, chartData.concat([1]));
                    var bars = document.createElement('div');
                    bars.style.display = 'flex';
                    bars.style.alignItems = 'flex-end';
                    bars.style.height = '180px';
                    bars.style.gap = '6px';
                    bars.style.paddingTop = '1rem';

                    chartData.forEach(function (value, index) {
                        var wrapper = document.createElement('div');
                        wrapper.style.flex = '1';
                        wrapper.style.display = 'flex';
                        wrapper.style.flexDirection = 'column';
                        wrapper.style.alignItems = 'center';
                        wrapper.style.height = '100%';

                        var barContainer = document.createElement('div');
                        barContainer.style.flex = '1';
                        barContainer.style.display = 'flex';
                        barContainer.style.alignItems = 'flex-end';
                        barContainer.style.width = '100%';
                        barContainer.style.justifyContent = 'center';

                        var bar = document.createElement('div');
                        bar.style.width = '20px';
                        bar.style.borderRadius = '4px 4px 0 0';
                        bar.style.background = '#2563eb';
                        bar.style.height = (value / max * 100) + '%';
                        bar.style.minHeight = '2px';
                        bar.style.transition = 'height 0.3s ease';
                        bar.title = value + ' réservations';

                        var label = document.createElement('div');
                        label.textContent = months[index];
                        label.style.fontSize = '0.75rem';
                        label.style.marginTop = '0.5rem';
                        label.style.color = '#6b7280';
                        label.style.fontWeight = '500';

                        var valueLabel = document.createElement('div');
                        valueLabel.textContent = value;
                        valueLabel.style.fontSize = '0.7rem';
                        valueLabel.style.color = '#4b5563';
                        valueLabel.style.marginBottom = '0.25rem';

                        barContainer.appendChild(bar);
                        wrapper.appendChild(valueLabel);
                        wrapper.appendChild(barContainer);
                        wrapper.appendChild(label);
                        bars.appendChild(wrapper);
                    });
                    container.appendChild(bars);
                }

                // Graphique du statut des ressources
                var statusData = @json($ressourcesByStatus);
                var statusContainer = document.getElementById('status-chart');
                if (statusContainer) {
                    var total = 0;
                    Object.keys(statusData).forEach(function (key) {
                        total += statusData[key];
                    });

                    var colors = {
                        'disponible': '#16a34a',
                        'réservé': '#2563eb',
                        'en maintenance': '#ea580c',
                        'inactif': '#6b7280',
                        'occupé': '#b91c1c',
                        'en_attente': '#f59e0b'
                    };

                    var labelsFr = {
                        'disponible': 'Disponible',
                        'réservé': 'Réservé',
                        'en maintenance': 'Maintenance',
                        'inactif': 'Inactif',
                        'occupé': 'Occupé',
                        'en_attente': 'En attente'
                    };

                    // Trier par quantité
                    var sortedStatuses = Object.keys(statusData).sort(function (a, b) {
                        return statusData[b] - statusData[a];
                    });

                    sortedStatuses.forEach(function (status) {
                        var count = statusData[status];
                        var percentage = total > 0 ? Math.round((count / total) * 100) : 0;

                        var row = document.createElement('div');
                        row.style.marginBottom = '1rem';

                        var header = document.createElement('div');
                        header.style.display = 'flex';
                        header.style.justifyContent = 'space-between';
                        header.style.marginBottom = '0.5rem';

                        var label = document.createElement('div');
                        label.textContent = (labelsFr[status] || status) + ' (' + count + ')';
                        label.style.fontSize = '0.875rem';
                        label.style.fontWeight = '500';
                        label.style.color = '#374151';

                        var percentLabel = document.createElement('div');
                        percentLabel.textContent = percentage + '%';
                        percentLabel.style.fontSize = '0.875rem';
                        percentLabel.style.color = '#6b7280';
                        percentLabel.style.fontWeight = '600';

                        var barWrapper = document.createElement('div');
                        barWrapper.style.height = '10px';
                        barWrapper.style.background = '#e5e7eb';
                        barWrapper.style.borderRadius = '9999px';
                        barWrapper.style.overflow = 'hidden';
                        barWrapper.style.position = 'relative';

                        var bar = document.createElement('div');
                        bar.style.height = '100%';
                        bar.style.borderRadius = '9999px';
                        bar.style.width = percentage + '%';
                        bar.style.background = colors[status] || '#4b5563';
                        bar.style.transition = 'width 0.5s ease';

                        header.appendChild(label);
                        header.appendChild(percentLabel);
                        barWrapper.appendChild(bar);

                        row.appendChild(header);
                        row.appendChild(barWrapper);
                        statusContainer.appendChild(row);
                    });
                }
            });
        </script>
    @endpush

@endsection