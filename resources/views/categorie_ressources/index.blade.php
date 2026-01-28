<!-- resources/views/categorie_ressources/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gestion des Catégories - DataCenterPro')

@section('content')
<div class="main-container">
    <!-- En-tête de page -->
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">Catégories de Ressources</h1>
            <p class="page-subtitle">Explorez les catégories de ressources disponibles dans le Data Center</p>
        </div>

        <!-- Indicateur du rôle -->
        <div class="role-indicator">
            @auth
            @if(auth()->user()->role === 'admin')
            <span class="badge badge-admin">
                <i class="fas fa-user-cog"></i> Administrateur
            </span>
            @elseif(auth()->user()->role === 'responsable')
            <span class="badge badge-responsable">
                <i class="fas fa-user-shield"></i> Responsable
            </span>
            @else
            <span class="badge badge-user">
                <i class="fas fa-user"></i> Utilisateur
            </span>
            @endif
            @else
            <span class="badge badge-guest">
                <i class="fas fa-user-clock"></i> Invité
            </span>
            @endauth
        </div>
    </div>

    <!-- Barre de recherche simple -->
    @auth
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('categorie_ressources.index') }}" class="search-form">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Rechercher une catégorie..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    @if(request()->has('search'))
                    <a href="{{ route('categorie_ressources.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Effacer
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Section pour Responsable: Mes catégories -->
    @if(auth()->user()->role === 'responsable' && isset($myCategories) && $myCategories->count() > 0)
    <div class="section-header mb-3">
        <h2 class="section-title">
            <i class="fas fa-user-circle"></i>
            Mes Catégories
        </h2>
        <span class="section-badge">{{ $myCategories->count() }} catégorie(s)</span>
    </div>

    <div class="cards-grid mb-5">
        @foreach($myCategories as $categorie)
        <div class="category-card" data-id="{{ $categorie->id_categorie }}">
            <a href="{{ route('categorie_ressources.show', $categorie->id_categorie) }}">
                <!-- Image de la catégorie -->
                <div class="category-image">
                    @if($categorie->img)
                    <img src="{{ asset('storage/' . $categorie->img) }}" alt="{{ $categorie->nom }}">
                    @else
                    <div class="category-image-placeholder">
                        <i class="fas fa-folder"></i>
                    </div>
                    @endif
                </div>

                <!-- Contenu de la carte -->
                <div class="category-content">
                    <h3 class="category-title">{{ $categorie->nom }}</h3>
                    <p class="category-description">
                        {{ $categorie->description ? Str::limit($categorie->description, 80) : 'Aucune description' }}
                    </p>
                    <div class="category-stats">
                        <span class="stat-item">
                            <i class="fas fa-server"></i>
                            <strong>{{ $categorie->ressources_count ?? $categorie->ressources->count() }}</strong> ressources
                        </span>
                    </div>
                    <div class="category-meta">
                        <span class="meta-item">
                            <i class="fas fa-user"></i>
                            Vous
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-calendar"></i>
                            {{ $categorie->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                <!-- Actions au survol -->
                <div class="category-actions">
                    <a href="{{ route('categorie_ressources.show', $categorie->id_categorie) }}"
                        class="action-btn action-view" title="Voir détails">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('categorie_ressources.edit', $categorie->id_categorie) }}"
                        class="action-btn action-edit" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('categorie_ressources.destroy', $categorie->id_categorie) }}"
                        method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="action-btn action-delete"
                            title="Supprimer"
                            {{ ($categorie->ressources_count ?? $categorie->ressources->count()) > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif
    @endauth

    <div class="section-header mb-3">
        <h2 class="section-title">
            <i class="fas fa-th-large"></i>
            @if(auth()->check() && auth()->user()->role === 'responsable' && isset($myCategories) && $myCategories->count() > 0)
            Autres Catégories
            @else
            Toutes les Catégories
            @endif
        </h2>
        <span class="section-badge">{{ $displayCategories->count() }} catégorie(s)</span>
    </div>

    @if($displayCategories->count() > 0)
    <div class="cards-grid">
        <!-- Carte Ajouter pour Administrateur -->
        @auth
        @if( auth()?->user()->role === 'admin')
        <a class="category-card add-card" href="{{ route('categorie_ressources.create') }}" class="category-overlay">
            <div class="add-card-content">
                <div class="add-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <h3 class="add-title">Nouvelle Catégorie</h3>
                <p class="add-description">Ajouter une nouvelle catégorie de ressources</p>
            </div>
        </a>
        @endif
        @endauth

        <!-- Cartes des catégories -->
        @foreach($displayCategories as $categorie)
        <div class="category-card" data-id="{{ $categorie->id_categorie }}">
            <a href="{{ route('categorie_ressources.show', $categorie->id_categorie) }}">
                <!-- Image de la catégorie -->
                <div class="category-image">
                    @if($categorie->img)
                    <img src="{{ asset('storage/' . $categorie->img) }}" alt="{{ $categorie->nom }}">
                    @else
                    <div class="category-image-placeholder">
                        <i class="fas fa-folder"></i>
                    </div>
                    @endif
                </div>

                <!-- Contenu de la carte -->
                <div class="category-content">
                    <h3 class="category-title">{{ $categorie->nom }}</h3>
                    <p class="category-description">
                        {{ $categorie->description ? Str::limit($categorie->description, 80) : 'Aucune description' }}
                    </p>
                    <div class="category-stats">
                        <span class="stat-item">
                            <i class="fas fa-server"></i>
                            <strong>{{ $categorie->ressources_count ?? $categorie->ressources->count() }}</strong> ressources
                        </span>
                    </div>
                </div>

                <!-- Actions au survol -->
                <div class="category-actions">
                    <!-- Voir détails (toujours visible) -->
                    <a href="{{ route('categorie_ressources.show', $categorie->id_categorie) }}"
                        class="action-btn action-view" title="Voir détails">
                        <i class="fas fa-eye"></i>
                    </a>

                    @auth
                    <!-- Modifier (admin ou responsable propriétaire) -->
                    @if(auth()->user()->role === 'admin' ||
                    (auth()->user()->role === 'responsable' && $categorie->user_id === auth()->id()))
                    <a href="{{ route('categorie_ressources.edit', $categorie->id_categorie) }}"
                        class="action-btn action-edit" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endif

                    <!-- Supprimer (admin ou responsable propriétaire) -->
                    @if(auth()->user()->role === 'admin' ||
                    (auth()->user()->role === 'responsable' && $categorie->user_id === auth()->id()))
                    <form action="{{ route('categorie_ressources.destroy', $categorie->id_categorie) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Supprimer cette catégorie ?')">
                        @csrf
                        @method('DELETE')
                        @php
                        $resourcesCount = $categorie->ressources_count ?? $categorie->ressources->count();
                        @endphp
                        <button type="submit"
                            class="action-btn action-delete"
                            title="Supprimer"
                            {{ $resourcesCount > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                    @endauth
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @else
    <!-- Message si aucune catégorie -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        <h3 class="empty-state-title">Aucune catégorie disponible</h3>
        <p class="empty-state-description">
            @auth
            @if(auth()->user()->role === 'admin')
            Commencez par créer votre première catégorie de ressources.
            <a href="{{ route('categorie_ressources.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Créer une catégorie
            </a>
            @else
            Aucune catégorie n'est disponible pour le moment.
            @endif
            @else
            Aucune catégorie n'est disponible pour le moment.
        <div class="mt-3">
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline">
                <i class="fas fa-user-plus"></i> Créer un compte
            </a>
        </div>
        @endauth
        </p>
    </div>
    @endif

    <!-- Guide rapide -->
    <div class="guide-section mt-5">
        <h3 class="guide-title">
            <i class="fas fa-lightbulb"></i>
            Comment utiliser les catégories ?
        </h3>
        <div class="guide-cards">
            <div class="guide-card">
                <div class="guide-icon">
                    <i class="fas fa-mouse-pointer"></i>
                </div>
                <h4>Cliquez sur une carte</h4>
                <p>Pour voir toutes les ressources de cette catégorie</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">
                    <i class="fas fa-hand-pointer"></i>
                </div>
                <h4>Survolez une carte</h4>
                <p>Pour voir les actions disponibles</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4>Rôle Responsable</h4>
                <p>Vos catégories sont séparées des autres</p>
            </div>
            <div class="guide-card">
                <div class="guide-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h4>Rôle Admin</h4>
                <p>Ajoutez de nouvelles catégories avec le bouton +</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Indicateur de rôle */
    .role-indicator {
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-admin {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        color: white;
    }

    .badge-responsable {
        background: linear-gradient(135deg, var(--warning), #d97706);
        color: white;
    }

    .badge-user {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .badge-guest {
        background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        color: white;
    }

    /* Section header */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 0 1rem 0;
        margin-bottom: 1.5rem;
        margin-top: 1.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--primary);
    }

    .section-badge {
        background-color: var(--primary-super-light);
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Grid des cartes */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Carte de catégorie */
    .category-card {
        background-color: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        cursor: pointer;
        height: 100%;
        min-height: 300px;
        display: flex;
        flex-direction: column;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
    }

    /* Image de la catégorie */
    .category-image {
        height: 260px;
        width: 100%;
        overflow: hidden;
        background-color: var(--surface-hover);
        position: relative;
    }

    .category-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .category-card:hover .category-image img {
        transform: scale(1.05);
    }

    .category-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
        font-size: 3rem;
    }

    /* Contenu de la carte */
    .category-content {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .category-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .category-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .category-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .stat-item i {
        color: var(--primary);
    }

    .stat-item strong {
        color: var(--text-main);
        font-weight: 600;
    }

    .category-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-light);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: var(--text-light);
        font-size: 0.75rem;
    }

    .meta-item i {
        font-size: 0.875rem;
    }

    /* Actions au survol */
    .category-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }

    .category-card:hover .category-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary);
        border: 1px solid var(--border);
        color: white;
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .action-view:hover {
        background-color: var(--info-light);
        color: var(--info);
        border-color: var(--info);
    }

    .action-edit:hover {
        background-color: var(--primary-super-light);
        color: var(--primary);
        border-color: var(--primary);
    }

    .action-delete:hover {
        background-color: var(--danger-light);
        color: var(--danger);
        border-color: var(--danger);
    }

    .action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Carte Ajouter pour Admin */
    .add-card {
        border: 2px dashed var(--border-dark);
        background-color: var(--surface-hover);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-card:hover {
        border-color: var(--primary);
        background-color: var(--primary-super-light);
    }

    .add-card-content {
        text-align: center;
        padding: 2rem;
        z-index: 2;
        position: relative;
    }

    .add-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .add-card:hover .add-icon {
        transform: scale(1.1);
    }

    .add-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .add-description {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    /* État vide */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background-color: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--text-light);
        margin-bottom: 1.5rem;
    }

    .empty-state-title {
        font-size: 1.5rem;
        color: var(--text-main);
        margin-bottom: 0.75rem;
    }

    .empty-state-description {
        color: var(--text-secondary);
        max-width: 500px;
        margin: 0 auto;
    }

    /* Guide section */
    .guide-section {
        padding: 2rem;
        background-color: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
    }

    .guide-title {
        font-size: 1.25rem;
        color: var(--text-main);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .guide-title i {
        color: var(--warning);
    }

    .guide-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }

    .guide-card {
        padding: 1.25rem;
        background-color: var(--surface-hover);
        border-radius: 8px;
        text-align: center;
    }

    .guide-icon {
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }

    .guide-card h4 {
        font-size: 1rem;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .guide-card p {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cards-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }

        .role-indicator {
            position: static;
            margin-bottom: 1rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes
        const cards = document.querySelectorAll('.category-card');

        // Confirmation avant suppression
        const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
        deleteForms.forEach(form => {
            form.onsubmit = function(e) {
                e.stopPropagation();
                if (!confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
                    e.preventDefault();
                    return false;
                }
                return true;
            };
        });

        // Effet de chargement doux
        setTimeout(() => {
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        }, 100);
    });
</script>
@endsection