<!-- resources/views/categorie_ressources/show.blade.php -->
@extends('layouts.app')

@section('title', $categorie->nom . ' - DataCenterPro')

@section('content')
<div class="main-container">
    <!-- En-tête de page amélioré -->
    <div class="header1">
        <div class="header-content">
            <div class="category-brand">
                @if($categorie->img)
                <div class="category-logo">
                    <img src="{{ asset('storage/' . $categorie->img) }}"
                        alt="{{ $categorie->nom }}"
                        class="category-image">
                </div>
                @endif
                <div class="category-info">
                    <h1 class="page-title">{{ $categorie->nom }}</h1>
                    <p class="category-description">{{ $categorie->description ?: 'Aucune description' }}</p>
                </div>
            </div>

            <div class="header-actions">
                @auth
                @if(auth()->user()->role === 'admin' || $categorie->user_id === auth()->id())
                <a href="{{ route('categorie_ressources.edit', $categorie->id_categorie) }}" class="btn btn-edit">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Carte de détails principale -->
    <div class="category-details-card">
        <div class="card-header">
            <div class="header-title">
                <i class="fas fa-info-circle"></i>
                <h3>Détails de la Catégorie</h3>
            </div>
        </div>

        <div class="card-body">
            <div class="details-container">
                <!-- Informations de base -->
                <div class="basic-info-section">
                    <div class="info-item manager-info">
                        <div class="info-label">
                            <i class="fas fa-user-shield"></i>
                            <span>Responsable</span>
                        </div>
                        <div class="info-value">
                            <div class="manager-details">
                                @if($categorie->user->img)
                                <img src="{{ asset('storage/user/' . $categorie->user->img) }}"
                                    alt="{{ $categorie->user->prenom }}"
                                    class="manager-avatar">
                                @endif
                                <div class="manager-text">
                                    <strong>{{ $categorie->user->prenom }} {{ $categorie->user->nom }}</strong>
                                    <span class="manager-role">{{ ucfirst($categorie->user->role) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-item dates-info">
                        <div class="date-item">
                            <div class="date-label">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Créée le</span>
                            </div>
                            <div class="date-value">{{ $categorie->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="date-item">
                            <div class="date-label">
                                <i class="fas fa-clock"></i>
                                <span>Dernière modification</span>
                            </div>
                            <div class="date-value">{{ $categorie->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                @auth
                @if(auth()->user()->role === 'admin' ||
                (auth()->user()->role === 'responsable' && $categorie->user_id === auth()->id()))
                <!-- Statistiques améliorées -->
                <div class="stats-section">
                    <h4 class="section-title">
                        <i class="fas fa-chart-pie"></i>
                        <span>Statistiques des Ressources</span>
                    </h4>

                    <div class="stats-cards-grid">
                        <div class="stat-card total-card">
                            <div class="card-content">
                                <div class="stat-icon">
                                    <i class="fas fa-server"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-number">{{ $stats['total_ressources'] }}</h3>
                                    <span class="stat-label">Total</span>
                                </div>
                            </div>
                        </div>

                        <div class="stat-card available-card">
                            <div class="card-content">
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-number">{{ $stats['ressources_disponibles'] }}</h3>
                                    <span class="stat-label">Disponibles</span>
                                </div>
                            </div>
                        </div>

                        <div class="stat-card reserved-card">
                            <div class="card-content">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-number">{{ $stats['ressources_reservees'] }}</h3>
                                    <span class="stat-label">Réservées</span>
                                </div>
                            </div>
                        </div>

                        <div class="stat-card maintenance-card">
                            <div class="card-content">
                                <div class="stat-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="stat-info">
                                    <h3 class="stat-number">{{ $stats['ressources_maintenance'] }}</h3>
                                    <span class="stat-label">Maintenance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth
                @endif

                <!-- Actions rapides -->
                <div class="quick-actions-section">
                    <h4 class="section-title">
                        <i class="fas fa-bolt"></i>
                        <span>Actions Rapides</span>
                    </h4>

                    <div class="actions-grid">
                        <a href="{{ route('ressources.index') }}"
                            class="action-btn view-resources">
                            <div class="action-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="action-content">
                                <strong>Voir toutes les ressources</strong>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        @auth
                        @if(auth()->user()->role === 'admin' || $categorie->user_id === auth()->id())
                        <a href="{{ route('ressources.create') }}?categorie={{ $categorie->id_categorie }}"
                            class="action-btn add-resource">
                            <div class="action-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="action-content">
                                <strong>Ajouter une ressource</strong>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <form action="{{ route('categorie_ressources.destroy', $categorie->id_categorie) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="action-btn delete-category {{ $categorie->ressources->count() > 0 ? 'disabled' : '' }}"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')"
                                {{ $categorie->ressources->count() > 0 ? 'disabled' : '' }}
                                style="width: 100%">
                                <div class="action-icon">
                                    <i class="fas fa-trash-alt"></i>
                                </div>
                                <div class="action-content">
                                    <strong>Supprimer la catégorie</strong>
                                    @if($categorie->ressources->count() > 0)
                                    <small class="warning-text">Vider la catégorie d'abord</small>
                                    @else
                                    <small>Action irréversible</small>
                                    @endif
                                </div>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des ressources -->
    <div class="resources-section">
        <div class="section-header">
            <div class="header-left">
                <h2 class="section-title">
                    <i class="fas fa-server"></i>
                    <span>Ressources dans cette catégorie</span>
                    <span class="resources-count">{{ $categorie->ressources->count() }}</span>
                </h2>
                <p class="section-subtitle">Gérez et consultez les ressources disponibles</p>
            </div>

            @auth
            <div class="header-right filter-control">
                <select id="statusFilter" class="status-filter">
                    <option value="">Tous les statuts</option>
                    <option value="disponible">Disponible</option>
                    <option value="reserve">Réservé</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            @endauth
        </div>

        <!-- Liste des ressources -->
        @if($categorie->ressources->count() > 0)
        <div class="resources-grid">
            @foreach($categorie->ressources as $index => $ressource)
            @php
            $resourcesCount = $ressource->reservations->where('statut', 'approuvee')->where('date_fin', '>=', now())->count();
            $badgeClass = [
            'disponible' => 'success',
            'reserve' => 'warning',
            'maintenance' => 'danger',
            'indisponible' => 'secondary'
            ][$ressource->statut] ?? 'secondary';
            @endphp

            <div class="resource-card" data-status="{{ $ressource->statut }}">
                <!-- Card Header -->
                <div class="card-header">
                    <div class="status-indicator">
                        <span class="status-badge {{ $badgeClass }}">
                            <i class="fas fa-circle"></i>
                            {{ ucfirst($ressource->statut) }}
                        </span>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('ressources.show', $ressource->id_ressource) }}"
                            class="action-icon view-icon" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </a>

                        @auth
                        @if(auth()->user()->role === 'admin' ||
                        (auth()->user()->role === 'responsable' && $categorie->user_id === auth()->id()))
                        <a href="{{ route('ressources.edit', $ressource->id_ressource) }}"
                            class="action-icon edit-icon" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('ressources.destroy', $ressource->id_ressource) }}"
                            method="POST"
                            class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="action-icon delete-icon"
                                title="Supprimer"
                                onclick="return confirm('Supprimer cette ressource ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>
                </div>

                <!-- Resource Image -->
                <div class="resource-image">
                    @if($ressource->img)
                    <img src="{{ asset('storage/ressource/' . $ressource->img) }}"
                        alt="{{ $ressource->nom }}"
                        class="resource-img">
                    @else
                    <div class="image-placeholder">
                        <i class="fas fa-server"></i>
                    </div>
                    @endif
                </div>

                <!-- Resource Content -->
                <div class="resource-content">
                    <h3 class="resource-title">{{ $ressource->nom }}</h3>

                    <p class="resource-description">
                        {{ $ressource->description ? Str::limit($ressource->description, 100) : 'Aucune description' }}
                    </p>

                    <!-- Specifications -->
                    <div class="specifications-grid">
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <div class="spec-details">
                                <span class="spec-label">CPU</span>
                                <span class="spec-value">{{ $ressource->cpu }}</span>
                            </div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-memory"></i>
                            </div>
                            <div class="spec-details">
                                <span class="spec-label">RAM</span>
                                <span class="spec-value">{{ $ressource->ram }}</span>
                            </div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-hdd"></i>
                            </div>
                            <div class="spec-details">
                                <span class="spec-label">Stockage</span>
                                <span class="spec-value">{{ $ressource->capacite_stockage }}</span>
                            </div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <div class="spec-details">
                                <span class="spec-label">Bande passante</span>
                                <span class="spec-value">{{ $ressource->bande_passante }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="resource-meta">
                        @if(Auth::user() && ( Auth::user()->role === 'admin' || Auth::user()->role === 'responsable'))
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $ressource->localisation }}</span>
                        </div>
                        @endif

                        @if($resourcesCount > 0)
                        <div class="meta-item reservations">
                            <i class="fas fa-calendar-check"></i>
                            <span>{{ $resourcesCount }} réservation(s) active(s)</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer">
                    @if($ressource->statut === 'disponible')
                    @auth
                    <a href="{{ route('reservations.create', ['ressource' => $ressource->id_ressource]) }}"
                        class="btn btn-reserve">
                        <i class="fas fa-calendar-check"></i>
                        <span>Réserver</span>
                    </a>

                    @else
                    <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                        class="btn btn-reserve">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Se connecter pour réserver</span>
                    </a>
                    @endauth
                    @elseif($ressource->statut === 'reserve')
                    <button class="btn btn-reserved" disabled>
                        <i class="fas fa-clock"></i>
                        <span>Déjà réservé</span>
                    </button>
                    @elseif($ressource->statut === 'maintenance')
                    <button class="btn btn-maintenance" disabled>
                        <i class="fas fa-tools"></i>
                        <span>En maintenance</span>
                    </button>
                    @else
                    <button class="btn btn-unavailable" disabled>
                        <i class="fas fa-times-circle"></i>
                        <span>Indisponible</span>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-content">
                <div class="empty-icon">
                    <i class="fas fa-server"></i>
                </div>
                <h3>Aucune ressource dans cette catégorie</h3>
                <p>Commencez par ajouter des ressources à cette catégorie.</p>
                <div class="empty-actions">
                    @auth
                    @if(auth()->user()->role === 'admin' || $categorie->user_id === auth()->id())
                    <a href="{{ route('ressources.create') }}?categorie={{ $categorie->id_categorie }}"
                        class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter une ressource</span>
                    </a>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Se connecter</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline">
                        <i class="fas fa-user-plus"></i>
                        <span>Créer un compte</span>
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Guide d'utilisation -->
    <div class="guide-section">
        <div class="guide-header">
            <h3>
                <i class="fas fa-lightbulb"></i>
                <span>Comment utiliser les ressources ?</span>
            </h3>
        </div>

        <div class="guide-grid">
            <div class="guide-card">
                <div class="guide-icon reservation">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4>Réservation</h4>
                <p>Cliquez sur "Réserver" pour les ressources disponibles</p>
            </div>

            <div class="guide-card">
                <div class="guide-icon details">
                    <i class="fas fa-eye"></i>
                </div>
                <h4>Voir détails</h4>
                <p>Cliquez sur l'icône 👁️ pour plus d'informations</p>
            </div>

            <div class="guide-card">
                <div class="guide-icon management">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h4>Gestion</h4>
                <p>Admin/Responsable: modifiez/supprimez avec les icônes</p>
            </div>

            <div class="guide-card">
                <div class="guide-icon filter">
                    <i class="fas fa-filter"></i>
                </div>
                <h4>Filtrage</h4>
                <p>Utilisez le filtre pour voir les ressources par statut</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* ============================================
   EN-TÊTE DE PAGE AMÉLIORÉ
============================================ */


    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 2;
    }

    .category-brand {
        display: flex;
        align-items: center;
        gap: var(--space-lg);
        flex: 1;
    }

    .category-logo {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .category-info {
        flex: 1;
    }

    .page-title {
        font-size: var(--font-size-3xl);
        font-weight: 800;
        color: white;
        margin-bottom: var(--space-xs);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .category-description {
        color: rgba(255, 255, 255, 0.9);
        font-size: var(--font-size-lg);
        max-width: 600px;
    }

    .header-actions {
        position: absolute;
        right: 10px;
        top: 25%;

    }

    .btn-back,
    .btn-edit {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-lg);
        font-weight: 600;
        transition: all var(--transition-fast);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.1);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(-2px);
    }

    .btn-edit {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary);
        border: none;
    }

    .btn-edit:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ============================================
   CARTE DE DÉTAILS PRINCIPALE
============================================ */
    .category-details-card {
        background: var(--surface);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-lg);
        margin-bottom: var(--space-xl);
        overflow: hidden;
    }

    .category-details-card .card-header {
        background: linear-gradient(to right, var(--primary-super-light) 0%, var(--surface) 100%);
        padding: var(--space-lg);
        border-bottom: 1px solid var(--border);
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }

    .header-title i {
        color: var(--primary);
        font-size: var(--font-size-lg);
    }

    .header-title h3 {
        font-size: var(--font-size-xl);
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }

    .card-body {
        padding: var(--space-xl);
    }

    .details-container {
        display: flex;
        flex-direction: column;
        gap: var(--space-xl);
    }

    /* Informations de base */
    .basic-info-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-lg);
        padding: var(--space-lg);
        background: var(--surface-hover);
        border-radius: var(--radius-lg);
    }

    .info-item {
        padding: var(--space-md);
    }

    .manager-info .info-label {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
        color: var(--text-muted);
        font-size: var(--font-size-sm);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .manager-info .info-label i {
        color: var(--primary);
    }

    .manager-details {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }

    .manager-avatar {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-full);
        object-fit: cover;
        border: 3px solid var(--primary);
        box-shadow: var(--shadow-sm);
    }

    .manager-text {
        display: flex;
        flex-direction: column;
    }

    .manager-text strong {
        font-size: var(--font-size-lg);
        color: var(--text-main);
    }

    .manager-role {
        font-size: var(--font-size-sm);
        color: var(--text-muted);
        background: var(--primary-super-light);
        padding: 2px 8px;
        border-radius: var(--radius-full);
        display: inline-block;
        margin-top: 2px;
        max-width: fit-content;
    }

    .dates-info {
        display: flex;
        flex-direction: column;
        gap: var(--space-md);
    }

    .date-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-sm);
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }

    .date-label {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        color: var(--text-muted);
        font-size: var(--font-size-sm);
    }

    .date-label i {
        color: var(--primary);
    }

    .date-value {
        font-weight: 600;
        color: var(--text-main);
    }

    /* Statistiques améliorées */
    .stats-section {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-size: var(--font-size-lg);
        font-weight: 700;
        color: var(--text-main);
        margin: 8px 0;
    }

    .section-title i {
        color: var(--primary);
        background: var(--primary-super-light);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-full);
    }

    .total-resources {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: var(--primary-super-light);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-lg);
    }

    .total-count {
        font-size: var(--font-size-2xl);
        font-weight: 800;
        color: var(--primary);
        line-height: 1;
    }

    .total-label {
        font-size: var(--font-size-xs);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-md);
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-md);
        border: 1px solid var(--border);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .total-card::before {
        background: var(--primary);
    }

    .available-card::before {
        background: var(--success);
    }

    .reserved-card::before {
        background: var(--warning);
    }

    .maintenance-card::before {
        background: var(--danger);
    }

    .card-content {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-sm);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--font-size-xl);
        flex-shrink: 0;
    }

    .total-card .stat-icon {
        background: var(--primary-super-light);
        color: var(--primary);
    }

    .available-card .stat-icon {
        background: var(--success-light);
        color: var(--success);
    }

    .reserved-card .stat-icon {
        background: var(--warning-light);
        color: var(--warning);
    }

    .maintenance-card .stat-icon {
        background: var(--danger-light);
        color: var(--danger);
    }

    .stat-info {
        flex: 1;
    }

    .stat-number {
        font-size: var(--font-size-2xl);
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
        line-height: 1;
    }

    .total-card .stat-number {
        color: var(--primary);
    }

    .available-card .stat-number {
        color: var(--success);
    }

    .reserved-card .stat-number {
        color: var(--warning);
    }

    .maintenance-card .stat-number {
        color: var(--danger);
    }

    .stat-label {
        display: block;
        font-size: var(--font-size-sm);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-top: 2px;
    }

    .card-progress {
        height: 6px;
        background: var(--border);
        border-radius: var(--radius-full);
        overflow: hidden;
        margin-top: var(--space-sm);
    }

    .progress-bar {
        height: 100%;
        border-radius: var(--radius-full);
        transition: width 1s ease;
    }

    .total-card .progress-bar {
        background: var(--primary);
    }

    .available-card .progress-bar {
        background: var(--success);
    }

    .reserved-card .progress-bar {
        background: var(--warning);
    }

    .maintenance-card .progress-bar {
        background: var(--danger);
    }

    /* Actions rapides */
    .quick-actions-section {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        border: 1px solid var(--border);
    }

    .quick-actions-section .section-title {
        margin-bottom: var(--space-lg);
    }

    .actions-grid {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md);
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        text-align: left;
        transition: all var(--transition-normal);
        cursor: pointer;
        text-decoration: none;
        color: var(--text-main);
        position: relative;
        border-left: 4px solid transparent;
    }

    .action-btn:hover {
        transform: translateX(4px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
    }

    .view-resources {
        border-left-color: var(--primary);
    }

    .view-resources:hover {
        background: var(--primary-super-light);
        border-color: var(--primary);
    }

    .add-resource {
        border-left-color: var(--success);
    }

    .add-resource:hover {
        background: var(--success-light);
        border-color: var(--success);
    }

    .delete-category {
        border-left-color: var(--danger);
    }

    .delete-category:hover {
        background: var(--danger-light);
        border-color: var(--danger);
    }

    .delete-category.disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .delete-category.disabled:hover {
        transform: none;
        box-shadow: none;
        background: var(--surface);
        border-color: var(--border);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--font-size-xl);
        flex-shrink: 0;
    }

    .view-resources .action-icon {
        background: var(--primary-super-light);
        color: var(--primary);
    }

    .add-resource .action-icon {
        background: var(--success-light);
        color: var(--success);
    }

    .delete-category .action-icon {
        background: var(--danger-light);
        color: var(--danger);
    }

    .action-content {
        flex: 1;
    }

    .action-content strong {
        display: block;
        font-size: var(--font-size-base);
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 2px;
    }

    .action-content small {
        display: block;
        font-size: var(--font-size-sm);
        color: var(--text-muted);
    }

    .warning-text {
        color: var(--danger) !important;
        font-weight: 600;
    }

    .action-btn i.fa-arrow-right {
        color: var(--text-muted);
        transition: transform var(--transition-fast);
    }

    .action-btn:hover i.fa-arrow-right {
        transform: translateX(4px);
        color: var(--primary);
    }

    /* Section des ressources */
    .resources-section {
        margin-top: var(--space-xl);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xl);
        padding-bottom: var(--space-md);
    }

    .header-left {
        flex: 1;
    }

    .header-left .section-title {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-size: var(--font-size-2xl);
        color: var(--text-main);
        margin-bottom: var(--space-sm);
    }

    .resources-count {
        background: var(--primary);
        color: white;
        padding: 4px 12px;
        border-radius: var(--radius-full);
        font-size: var(--font-size-sm);
        font-weight: 600;
    }

    .section-subtitle {
        color: var(--text-muted);
        font-size: var(--font-size-base);
        margin: 0;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }

    .status-filter {
        padding: 8px 16px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--text-main);
        font-size: var(--font-size-sm);
        min-width: 150px;
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .status-filter:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
    }

    .btn-add-resource {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: 8px 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 600;
        transition: all var(--transition-fast);
    }

    .btn-add-resource:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
        color: white;
    }

    /* Grille des ressources */
    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-xl);
    }

    .resource-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        display: flex;
        flex-direction: column;
    }

    .resource-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
    }

    .resource-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md);
        background: var(--surface-hover);
        border-bottom: 1px solid var(--border);
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }

    .status-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: var(--radius-full);
        font-size: var(--font-size-xs);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge i {
        font-size: 8px;
    }

    .status-badge.success {
        background: var(--success-light);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .status-badge.warning {
        background: var(--warning-light);
        color: var(--warning-dark);
        border: 1px solid var(--warning);
    }

    .status-badge.danger {
        background: var(--danger-light);
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .status-badge.secondary {
        background: var(--surface-hover);
        color: var(--text-secondary);
        border: 1px solid var(--border);
    }

    .availability-tag {
        background: rgba(var(--success-rgb), 0.1);
        color: var(--success);
        padding: 4px 8px;
        border-radius: var(--radius-full);
        font-size: var(--font-size-xs);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .card-actions {
        display: flex;
        gap: var(--space-xs);
        opacity: 0;
        transform: translateX(10px);
        transition: all var(--transition-normal);
    }

    .resource-card:hover .card-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .action-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary);
        border: 1px solid var(--border);
        color: white;
        cursor: pointer;
        transition: all var(--transition-fast);
        font-size: var(--font-size-sm);
    }

    .action-icon:hover {
        transform: scale(1.1);
        box-shadow: var(--shadow-sm);
    }

    .view-icon:hover {
        background: var(--info-light);
        color: var(--info);
        border-color: var(--info);
    }

    .edit-icon:hover {
        background: var(--primary-super-light);
        color: var(--primary);
        border-color: var(--primary);
    }

    .delete-icon:hover {
        background: var(--danger-light);
        color: var(--danger);
        border-color: var(--danger);
    }

    /* Image de ressource */
    .resource-image {
        height: 180px;
        width: 100%;
        overflow: hidden;
        background: linear-gradient(135deg, var(--surface-hover) 0%, var(--border) 100%);
        position: relative;
    }

    .resource-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .resource-card:hover .resource-img {
        transform: scale(1.05);
    }

    .image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
        font-size: 3rem;
    }

    /* Contenu de ressource */
    .resource-content {
        padding: var(--space-lg);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .resource-title {
        font-size: var(--font-size-lg);
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: var(--space-sm);
        line-height: 1.3;
    }

    .resource-description {
        color: var(--text-secondary);
        font-size: var(--font-size-sm);
        line-height: 1.5;
        margin-bottom: var(--space-md);
        flex: 1;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Spécifications */
    .specifications-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        background: var(--surface-hover);
        border-radius: var(--radius-md);
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }

    .spec-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        background: var(--primary-super-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--font-size-sm);
        flex-shrink: 0;
    }

    .spec-details {
        display: flex;
        flex-direction: column;
    }

    .spec-label {
        font-size: var(--font-size-xs);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spec-value {
        font-size: var(--font-size-sm);
        font-weight: 600;
        color: var(--text-main);
    }

    /* Métadonnées */
    .resource-meta {
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
        margin-top: auto;
        padding-top: var(--space-md);
        border-top: 1px solid var(--border-light);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        color: var(--text-muted);
        font-size: var(--font-size-sm);
    }

    .meta-item i {
        width: 16px;
        color: var(--primary);
        flex-shrink: 0;
    }

    .meta-item.reservations {
        color: var(--warning);
    }

    .meta-item.reservations i {
        color: var(--warning);
    }

    /* Footer de carte */
    .card-footer {
        padding: var(--space-md);
        border-top: 1px solid var(--border);
        background: var(--surface-hover);
    }

    .btn-reserve,
    .btn-reserved,
    .btn-maintenance,
    .btn-unavailable {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
        padding: var(--space-md);
        border-radius: var(--radius-md);
        font-weight: 600;
        border: none;
        transition: all var(--transition-normal);
        cursor: pointer;
    }

    .btn-reserve {
        background: var(--primary);
        color: white;
    }

    .btn-reserve:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
        color: white;
    }

    .btn-reserved {
        background: var(--surface);
        color: var(--text-muted);
        border: 1px solid var(--border);
        cursor: not-allowed;
    }

    .btn-maintenance {
        background: var(--warning-light);
        color: var(--warning-dark);
        border: 1px solid var(--warning);
        cursor: not-allowed;
    }

    .btn-unavailable {
        background: var(--danger-light);
        color: var(--danger);
        border: 1px solid var(--danger);
        cursor: not-allowed;
    }

    /* État vide */
    .empty-state {
        text-align: center;
        padding: var(--space-2xl);
        background: var(--surface);
        border: 2px dashed var(--border);
        border-radius: var(--radius-xl);
        margin: var(--space-xl) 0;
    }

    .empty-content {
        max-width: 500px;
        margin: 0 auto;
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--primary-light);
        margin-bottom: var(--space-lg);
    }

    .empty-state h3 {
        font-size: var(--font-size-xl);
        color: var(--text-main);
        margin-bottom: var(--space-sm);
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: var(--space-lg);
    }

    .empty-actions {
        display: flex;
        justify-content: center;
        gap: var(--space-md);
    }

    /* Guide d'utilisation */
    .guide-section {
        background: linear-gradient(135deg, var(--primary-super-light) 0%, var(--surface) 100%);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-top: var(--space-xl);
        border: 1px solid var(--border);
    }

    .guide-header {
        margin-bottom: var(--space-xl);
    }

    .guide-header h3 {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-size: var(--font-size-xl);
        color: var(--text-main);
        margin: 0;
    }

    .guide-header i {
        color: var(--warning);
    }

    .guide-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: var(--space-lg);
    }

    .guide-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        text-align: center;
        border: 1px solid var(--border);
        transition: all var(--transition-normal);
    }

    .guide-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .guide-icon {
        width: 64px;
        height: 64px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
        font-size: var(--font-size-2xl);
    }

    .guide-icon.reservation {
        background: var(--success-light);
        color: var(--success);
    }

    .guide-icon.details {
        background: var(--info-light);
        color: var(--info);
    }

    .guide-icon.management {
        background: var(--primary-super-light);
        color: var(--primary);
    }

    .guide-icon.filter {
        background: var(--warning-light);
        color: var(--warning);
    }

    .guide-card h4 {
        font-size: var(--font-size-lg);
        color: var(--text-main);
        margin-bottom: var(--space-sm);
        font-weight: 600;
    }

    .guide-card p {
        color: var(--text-secondary);
        font-size: var(--font-size-sm);
        line-height: 1.5;
        margin: 0;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .resource-card {
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: calc(var(--index, 0) * 0.1s);
        opacity: 0;
    }

    /* Animation pour les statistiques */
    @keyframes countUp {
        from {
            transform: translateY(10px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .stat-number {
        animation: countUp 0.6s ease forwards;
        animation-delay: calc(var(--card-index, 0) * 0.2s);
        opacity: 0;
    }

    /* Variables pour les couleurs RGB */
    :root {
        --primary-rgb: 59, 130, 246;
        --success-rgb: 16, 185, 129;
        --warning-rgb: 245, 158, 11;
        --danger-rgb: 239, 68, 68;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes de ressources
        const resourceCards = document.querySelectorAll('.resource-card');
        resourceCards.forEach((card, index) => {
            card.style.setProperty('--index', index);
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Animation des statistiques
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach((number, index) => {
            number.style.setProperty('--card-index', index);
            number.style.animationDelay = `${index * 0.2}s`;
        });

        // Filtrage par statut
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                const selectedStatus = this.value;

                resourceCards.forEach(card => {
                    const cardStatus = card.getAttribute('data-status');

                    if (!selectedStatus || cardStatus === selectedStatus) {
                        card.style.display = 'flex';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        }

        // Tooltip pour les actions
        const actionIcons = document.querySelectorAll('.action-icon');
        actionIcons.forEach(icon => {
            const title = icon.getAttribute('title');
            if (title) {
                icon.addEventListener('mouseenter', function(e) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'action-tooltip';
                    tooltip.textContent = title;
                    tooltip.style.position = 'fixed';
                    tooltip.style.background = 'var(--surface-dark)';
                    tooltip.style.color = 'var(--text-on-primary)';
                    tooltip.style.padding = '6px 12px';
                    tooltip.style.borderRadius = 'var(--radius-md)';
                    tooltip.style.fontSize = 'var(--font-size-xs)';
                    tooltip.style.zIndex = '9999';
                    tooltip.style.whiteSpace = 'nowrap';
                    tooltip.style.pointerEvents = 'none';
                    tooltip.style.boxShadow = 'var(--shadow-lg)';

                    document.body.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.top = (rect.top - 40) + 'px';
                    tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';

                    this.tooltip = tooltip;
                });

                icon.addEventListener('mouseleave', function() {
                    if (this.tooltip) {
                        this.tooltip.remove();
                        this.tooltip = null;
                    }
                });
            }
        });

        // Confirmation avant suppression
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.stopPropagation();
                if (!confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        });

        // Empêcher la propagation du clic sur les actions
        actionIcons.forEach(icon => {
            icon.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Redirection vers login pour les non-authentifiés
        const loginButtons = document.querySelectorAll('.btn-reserve[href*="login"]');
        loginButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                const currentUrl = encodeURIComponent(window.location.href);
                window.location.href = `{{ route('login') }}?redirect=${currentUrl}`;
                e.preventDefault();
            });
        });

        // Effet de survol sur les cartes
        resourceCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });

            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });
    });
</script>
@endsection