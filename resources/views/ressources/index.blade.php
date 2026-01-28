@extends('layouts.app')

@section('content')
<div class="main-container">
    <!-- Header Section -->
    <div>
        <h1 class="page-title text-gradient">
            <i class="fas fa-server"></i>
            Catalogue des Ressources
        </h1>
        <p class="page-subtitle">
            Vue d'ensemble des ressources du centre de données.
        </p>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        @auth
        @if(in_array(auth()->user()->role, ['admin']))
        <a href="{{ route('ressources.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Ajouter une ressource
        </a>
        @endif

        @if(auth()->user()->role === 'user')
        <a href="{{ route('reservations.create') }}" class="btn btn-success">
            <i class="fas fa-calendar-plus"></i>
            Nouvelle réservation
        </a>
        @endif
        @endauth

        @guest
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i>
            Se connecter
        </a>
        @endguest
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form action="{{ route('ressources.index') }}" method="GET" class="filters-form">
            <div class="filter-group">
                <div class="filter-item">
                    <label for="search" class="filter-label">
                        <i class="fas fa-search"></i>
                    </label>
                    <input type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control filter-input"
                        placeholder="Rechercher une ressource...">
                </div>

                <div class="filter-item">
                    <label for="statut" class="filter-label">
                        <i class="fas fa-circle"></i>
                    </label>
                    <select id="statut" name="statut" class="form-control filter-select">
                        <option value="">Tous les statuts</option>
                        <option value="disponible" {{ request('statut') == 'disponible' ? 'selected' : '' }}>
                            Disponible
                        </option>
                        <option value="occupé" {{ request('statut') == 'occupé' ? 'selected' : '' }}>
                            Occupé
                        </option>
                        <option value="maintenance" {{ request('statut') == 'maintenance' ? 'selected' : '' }}>
                            Maintenance
                        </option>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="categorie" class="filter-label">
                        <i class="fas fa-folder"></i>
                    </label>
                    <select id="categorie" name="categorie" class="form-control filter-select">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id_categorie }}"
                            {{ request('categorie') == $category->id_categorie ? 'selected' : '' }}>
                            {{ $category->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-filter">
                    <i class="fas fa-filter"></i>
                    Filtrer
                </button>

                @if(request()->hasAny(['search', 'statut', 'categorie']))
                <a href="{{ route('ressources.index') }}" class="btn btn-reset">
                    <i class="fas fa-times"></i>
                    Réinitialiser
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Resources Grid -->
    <div class="resources-container">
        @if($ressources->count() > 0)
        <div class="resources-grid">
            @foreach($ressources as $ressource)
            <div class="resource-card" data-status="{{ $ressource->statut }}">
                <!-- Card Header -->
                <div class="card-header">
                    <h3>{{ $ressource->nom }}</h3>
                    <span class="status-badge status-{{ $ressource->statut }}">
                        {{ $ressource->statut }}
                    </span>
                </div>

                <!-- Specifications -->
                <div class="specs-list">
                    <div class="spec-item">
                        <i class="fas fa-microchip"></i>
                        <span class="spec-label">CPU:</span>
                        <span class="spec-value">{{ $ressource->cpu }}</span>
                    </div>
                    <div class="spec-item">
                        <i class="fas fa-memory"></i>
                        <span class="spec-label">RAM:</span>
                        <span class="spec-value">{{ $ressource->ram }}</span>
                    </div>
                    <div class="spec-item">
                        <i class="fas fa-hdd"></i>
                        <span class="spec-label">Stockage:</span>
                        <span class="spec-value">{{ $ressource->capacite_stockage }}</span>
                    </div>
                    <div class="spec-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="spec-label">Localisation:</span>
                        <span class="spec-value">{{ $ressource->localisation }}</span>
                    </div>
                    <div class="spec-item">
                        <i class="fas fa-folder"></i>
                        <span class="spec-label">Catégorie:</span>
                        <span class="spec-value">{{ $ressource->categorie->nom ?? 'Non catégorisé' }}</span>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer">
                    <a href="{{ route('ressources.show', $ressource->id_ressource) }}"
                        class="btn btn-view">
                        <i class="fas fa-eye"></i>
                        Voir détails
                    </a>

                    @auth
                    <!-- Admin/Responsable Actions -->
                    @if(in_array(auth()->user()->role, ['admin', 'responsable']))
                    @if($ressource->categorie &&
                    (auth()->user()->role === 'admin' ||
                    $ressource->categorie->user_id == auth()->id()))
                    <a href="{{ route('ressources.edit', $ressource->id_ressource) }}"
                        class="btn btn-edit">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    @endif
                    @endif

                    <!-- User Actions -->
                    @if(auth()->user()->role === 'user' && $ressource->statut === 'disponible')
                    <a href="{{ route('reservations.create') }}?ressource={{ $ressource->id_ressource }}"
                        class="btn btn-reserve">
                        <i class="fas fa-calendar-check"></i>
                        Réserver
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($ressources->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                Page {{ $ressources->currentPage() }} sur {{ $ressources->lastPage() }}
                ({{ $ressources->total() }} ressources)
            </div>
            <div class="pagination-links">
                @if(!$ressources->onFirstPage())
                <a href="{{ $ressources->previousPageUrl() }}" class="pagination-link">
                    <i class="fas fa-chevron-left"></i>
                    Précédent
                </a>
                @endif

                @for($i = 1; $i <= $ressources->lastPage(); $i++)
                    @if($i == $ressources->currentPage())
                    <span class="pagination-link active">{{ $i }}</span>
                    @else
                    <a href="{{ $ressources->url($i) }}" class="pagination-link">{{ $i }}</a>
                    @endif
                    @endfor

                    @if($ressources->hasMorePages())
                    <a href="{{ $ressources->nextPageUrl() }}" class="pagination-link">
                        Suivant
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    @endif
            </div>
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-server"></i>
            </div>
            <h3>Aucune ressource trouvée</h3>
            <p>Aucune ressource ne correspond à vos critères de recherche.</p>
            @if(request()->hasAny(['search', 'statut', 'categorie']))
            <a href="{{ route('ressources.index') }}" class="btn btn-primary">
                <i class="fas fa-redo"></i>
                Voir toutes les ressources
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
    /* ============================================
   STYLES SIMPLES ET MODERNES
============================================ */

    /* Header Section */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .page-title i {
        font-size: 1.5rem;
    }

    /* User Role Badge */
    .user-role-badge .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-admin {
        background: var(--danger-light);
        color: var(--danger);
    }

    .badge-responsable {
        background: var(--warning-light);
        color: var(--warning-dark);
    }

    .badge-user {
        background: var(--success-light);
        color: var(--success);
    }

    .badge-guest {
        background: var(--secondary-light);
        color: var(--secondary-dark);
    }

    /* Quick Stats */
    .quick-stats {
        display: flex;
        gap: 2rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Bar */
    .action-bar {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: var(--surface);
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .action-bar .btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
    }

    /* Filters Section */
    .filters-section {
        background: var(--surface);
        padding: 1.25rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        color: var(--primary);
        font-size: 1rem;
        min-width: 24px;
    }

    .filter-input,
    .filter-select {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.95rem;
        background: var(--surface);
        transition: all 0.2s ease;
    }

    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 59, 130, 246), 0.1);
    }

    .btn-filter,
    .btn-reset {
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-reset {
        background: var(--surface);
        color: var(--text-secondary);
        border: 1px solid var(--border);
    }

    .btn-reset:hover {
        background: var(--surface-hover);
        color: var(--text-main);
    }

    /* Resources Grid */
    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    /* Resource Card */
    .resource-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .resource-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-light);
    }

    .card-header {
        padding: 1.25rem;
        background: linear-gradient(135deg, var(--primary-super-light) 0%, var(--surface) 100%);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-disponible {
        background: var(--success-light);
        color: var(--success);
    }

    .status-occupé {
        background: var(--warning-light);
        color: var(--warning-dark);
    }

    .status-maintenance {
        background: var(--danger-light);
        color: var(--danger);
    }

    .status-inactif {
        background: var(--secondary-light);
        color: var(--secondary-dark);
    }

    /* Specifications List */
    .specs-list {
        padding: 1.25rem;
        flex: 1;
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        background: var(--surface-hover);
        border-radius: 6px;
        transition: background 0.2s ease;
    }

    .spec-item:hover {
        background: var(--primary-super-light);
    }

    .spec-item i {
        color: var(--primary);
        width: 20px;
        text-align: center;
        font-size: 0.9rem;
    }

    .spec-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        min-width: 100px;
    }

    .spec-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-main);
        flex: 1;
    }

    /* Card Footer */
    .card-footer {
        padding: 1.25rem;
        border-top: 1px solid var(--border);
        background: var(--surface-hover);
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .card-footer .btn {
        flex: 1;
        min-width: 120px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-view {
        background: var(--info-light);
        color: var(--info);
        border: 1px solid var(--info);
    }

    .btn-edit {
        background: var(--primary-super-light);
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-reserve {
        background: var(--success-light);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .btn-login {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-view:hover {
        background: var(--info);
        color: white;
    }

    .btn-edit:hover {
        background: var(--primary);
        color: white;
    }

    .btn-reserve:hover {
        background: var(--success);
        color: white;
    }

    .btn-login:hover {
        background: var(--primary-dark);
        color: white;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-top: 2rem;
    }

    .pagination-info {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .pagination-links {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .pagination-link {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--text-main);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-link:hover {
        background: var(--primary-super-light);
        border-color: var(--primary);
        color: var(--primary);
    }

    .pagination-link.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: var(--surface);
        border: 2px dashed var(--border);
        border-radius: 10px;
        margin: 2rem 0;
    }

    .empty-icon {
        font-size: 3rem;
        color: var(--primary-light);
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: var(--text-main);
        margin-bottom: 0.5rem;
        font-size: 1.3rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .quick-stats {
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }

        .filter-group {
            flex-direction: column;
        }

        .filter-item {
            width: 100%;
        }

        .resources-grid {
            grid-template-columns: 1fr;
        }

        .pagination-container {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .card-footer .btn {
            min-width: 100%;
        }

        .specs-list {
            padding: 1rem;
        }

        .spec-item {
            flex-wrap: wrap;
        }

        .spec-label {
            min-width: 80px;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .action-bar {
            flex-direction: column;
        }

        .action-bar .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes
        const cards = document.querySelectorAll('.resource-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            }, 100);
        });

        // Tooltips pour les badges de statut
        const statusBadges = document.querySelectorAll('.status-badge');
        statusBadges.forEach(badge => {
            badge.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'status-tooltip';
                tooltip.textContent = this.textContent;
                tooltip.style.position = 'absolute';
                tooltip.style.background = 'var(--surface-dark)';
                tooltip.style.color = 'white';
                tooltip.style.padding = '4px 8px';
                tooltip.style.borderRadius = '4px';
                tooltip.style.fontSize = '12px';
                tooltip.style.zIndex = '1000';
                tooltip.style.whiteSpace = 'nowrap';

                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                tooltip.style.top = (rect.top - 30) + 'px';
                tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';

                this.tooltip = tooltip;
            });

            badge.addEventListener('mouseleave', function() {
                if (this.tooltip) {
                    this.tooltip.remove();
                    this.tooltip = null;
                }
            });
        });
    });
</script>

@endsection