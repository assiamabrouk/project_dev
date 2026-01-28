@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="page-header">
        <h1 class="page-title text-gradient">
            <i class="fas fa-server"></i>
            {{ $ressource->nom }}
        </h1>

        <!-- Status Badge -->
        <div class="status-container">
            <span class="status-badge status-{{ $ressource->statut }}">
                @switch($ressource->statut)
                @case('disponible')
                <i class="fas fa-check-circle"></i> Disponible
                @break
                @case('occupé')
                <i class="fas fa-user-clock"></i> Occupé
                @break
                @case('maintenance')
                <i class="fas fa-tools"></i> Maintenance
                @break
                @case('inactif')
                <i class="fas fa-power-off"></i> Inactif
                @break
                @endswitch
            </span>

            @if($ressource->categorie)
            <span class="category-badge">
                <i class="fas fa-folder"></i> {{ $ressource->categorie->nom }}
            </span>
            @endif
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Main Content -->
    <div class="content-grid">
        <!-- Left Column - Resource Info -->
        <div class="left-column">
            <!-- Description -->
            <div class="info-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-info-circle"></i>
                        Description
                    </h3>
                </div>
                <div class="card-body">
                    <p>{{ $ressource->description ?? 'Aucune description disponible' }}</p>
                </div>
            </div>

            <!-- Specifications -->
            <div class="info-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-microchip"></i>
                        Spécifications
                    </h3>
                </div>
                <div class="card-body">
                    <div class="specs-grid">
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <div class="spec-info">
                                <span class="spec-label">CPU</span>
                                <span class="spec-value">{{ $ressource->cpu }}</span>
                            </div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-memory"></i>
                            </div>
                            <div class="spec-info">
                                <span class="spec-label">RAM</span>
                                <span class="spec-value">{{ $ressource->ram }}</span>
                            </div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-hdd"></i>
                            </div>
                            <div class="spec-info">
                                <span class="spec-label">Stockage</span>
                                <span class="spec-value">{{ $ressource->capacite_stockage }}</span>
                            </div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <div class="spec-info">
                                <span class="spec-label">Bande passante</span>
                                <span class="spec-value">{{ $ressource->bande_passante }}</span>
                            </div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <div class="spec-info">
                                <span class="spec-label">Système</span>
                                <span class="spec-value">{{ $ressource->os }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image -->
            @if($ressource->img)
            <div class="info-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-image"></i>
                        Photo
                    </h3>
                </div>
                <div class="card-body">
                    <img
                        src="{{ asset('storage/ressource/'. $ressource->img) }}"
                        alt="{{ $ressource->nom }}"
                        class="resource-image">
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Actions & Metadata -->
        <div class="right-column">
            <!-- Quick Actions -->
            <div class="action-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-bolt"></i>
                        Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="actions-grid">
                        @auth
                        <!-- User Actions -->
                        @if(auth()->user()->role === 'user' && $ressource->statut === 'disponible')
                        <a href="{{ route('reservations.create') }}?ressource={{ $ressource->id_ressource }}"
                            class="action-btn reserve-btn">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Réserver</span>
                        </a>
                        @endif

                        <!-- Responsable Actions -->
                        @if(auth()->user()->role === 'responsable' &&
                        $ressource->categorie &&
                        $ressource->categorie->user_id == auth()->id())
                        <a href="{{ route('ressources.edit', $ressource->id_ressource) }}"
                            class="action-btn edit-btn">
                            <i class="fas fa-edit"></i>
                            <span>Modifier</span>
                        </a>

                        <!-- Change Status -->
                        <div class="status-actions">
                            <small>Changer le statut :</small>
                            <div class="status-buttons">
                                @if(Route::has('ressources.changeStatus'))
                                <form action="{{ route('ressources.changeStatus', $ressource->id_ressource) }}"
                                    method="POST"
                                    class="status-form">
                                    @csrf
                                    <input type="hidden" name="statut" value="disponible">
                                    <button type="submit"
                                        class="status-btn available {{ $ressource->statut == 'disponible' ? 'active' : '' }}"
                                        title="Disponible">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <form action="{{ route('ressources.changeStatus', $ressource->id_ressource) }}"
                                    method="POST"
                                    class="status-form">
                                    @csrf
                                    <input type="hidden" name="statut" value="occupé">
                                    <button type="submit"
                                        class="status-btn occupied {{ $ressource->statut == 'occupé' ? 'active' : '' }}"
                                        title="Occupé">
                                        <i class="fas fa-user"></i>
                                    </button>
                                </form>

                                <form action="{{ route('ressources.changeStatus', $ressource->id_ressource) }}"
                                    method="POST"
                                    class="status-form">
                                    @csrf
                                    <input type="hidden" name="statut" value="maintenance">
                                    <button type="submit"
                                        class="status-btn maintenance {{ $ressource->statut == 'maintenance' ? 'active' : '' }}"
                                        title="Maintenance">
                                        <i class="fas fa-tools"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Admin Actions -->
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('ressources.edit', $ressource->id_ressource) }}"
                            class="action-btn edit-btn">
                            <i class="fas fa-edit"></i>
                            <span>Modifier</span>
                        </a>

                        <form action="{{ route('ressources.destroy', $ressource->id_ressource) }}"
                            method="POST"
                            class="delete-form"
                            onsubmit="return confirm('Supprimer cette ressource ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn">
                                <i class="fas fa-trash"></i>
                                <span>Supprimer</span>
                            </button>
                        </form>
                        @endif
                        @endauth

                        @guest
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Se connecter pour réserver</span>
                        </a>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="meta-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-history"></i>
                        Informations
                    </h3>
                </div>
                <div class="card-body">
                    <div class="meta-list">
                        <div class="meta-item">
                            <i class="far fa-calendar-plus"></i>
                            <div class="meta-content">
                                <span class="meta-label">Créée le</span>
                                <span class="meta-value">{{ $ressource->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="meta-item">
                            <i class="far fa-calendar-check"></i>
                            <div class="meta-content">
                                <span class="meta-label">Modifiée le</span>
                                <span class="meta-value">{{ $ressource->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        @if($ressource->categorie && $ressource->categorie->user)
                        <div class="meta-item">
                            <i class="fas fa-user-shield"></i>
                            <div class="meta-content">
                                <span class="meta-label">Responsable</span>
                                <span class="meta-value">{{ $ressource->categorie->user->prenom }} {{ $ressource->categorie->user->nom }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
    <!-- Tabs Section -->
    <div class="tabs-section">
        <div class="tabs-header">
            @if(auth()->user()->canManageRessource($ressource))
            <button class="tab-btn active" data-tab="reservations">
                <i class="fas fa-calendar-alt"></i>
                Réservations
                @if(isset($ressource->reservations) && $ressource->reservations->count() > 0)
                <span class="badge">{{ $ressource->reservations->count() }}</span>
                @endif
            </button>

            <button class="tab-btn" data-tab="history">
                <i class="fas fa-history"></i>
                Historique
            </button>
            @endif

            <button class="tab-btn" data-tab="discussions">
                <i class="fas fa-comments"></i>
                Discussions
                @if(isset($stats['discussions_count']) && $stats['discussions_count'] > 0)
                <span class="badge">{{ $stats['discussions_count'] }}</span>
                @endif
            </button>
        </div>

        <div class="tabs-content">
            <!-- Reservations Tab -->
            @if(auth()->user()->canManageRessource($ressource))
            <div class="tab-pane active" id="reservations">
                @if(isset($ressource->reservations) && $ressource->reservations->count() > 0)
                <div class="reservations-list">
                    @foreach($ressource->reservations->take(5) as $reservation)
                    <div class="reservation-item">
                        <div class="reservation-header">
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ substr($reservation->utilisateur->prenom ?? 'U', 0, 1) }}{{ substr($reservation->utilisateur->nom ?? 'S', 0, 1) }}
                                </div>
                                <div class="user-details">
                                    <strong>{{ $reservation->utilisateur->prenom ?? '' }} {{ $reservation->utilisateur->nom ?? '' }}</strong>
                                    <small>{{ $reservation->utilisateur->email ?? '' }}</small>
                                </div>
                            </div>

                            <span class="reservation-status status-{{ $reservation->statut }}">
                                {{ $reservation->statut }}
                            </span>
                        </div>

                        <div class="reservation-dates">
                            <div class="date-item">
                                <i class="fas fa-play"></i>
                                <span>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="date-item">
                                <i class="fas fa-stop"></i>
                                <span>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        @if($reservation->commentaire)
                        <p class="reservation-comment">
                            <i class="fas fa-comment"></i>
                            {{ $reservation->commentaire }}
                        </p>
                        @endif

                        @if(auth()->check() &&
                        (auth()->user()->role === 'admin' ||
                        auth()->user()->id == $reservation->user_id))
                        <div class="reservation-actions">
                            <a href="{{ route('reservations.show', $reservation->id_reservation) }}"
                                class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i>
                                Voir
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    @if($ressource->reservations->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('reservations.index') }}?ressource={{ $ressource->id_ressource }}"
                            class="btn btn-outline">
                            Voir toutes les réservations ({{ $ressource->reservations->count() }})
                        </a>
                    </div>
                    @endif
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <h4>Aucune réservation</h4>
                    <p>Aucune réservation n'a été faite pour cette ressource.</p>
                    @if(auth()->user()->role === 'user' && $ressource->statut === 'disponible')
                    <a href="{{ route('reservations.create') }}?ressource={{ $ressource->id_ressource }}"
                        class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i>
                        Faire une réservation
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endif
            
            <!-- Discussions Tab -->
            <div class="tab-pane" id="discussions">
                <!-- New Discussion Form -->
                @auth
                <div class="new-discussion-form">
                    <h3 class="form-title">
                        <i class="fas fa-comment-medical"></i>
                        Ajouter un commentaire
                    </h3>
                    <form action="{{ route('discussions.store', $ressource->id_ressource) }}" method="POST">
                        @csrf
                        <div class="discussion-input-container">
                            <textarea 
                                name="message" 
                                class="discussion-textarea" 
                                placeholder="Partagez vos questions, suggestions ou retours sur cette ressource..."
                                rows="1"
                                required></textarea>
                            <div class="textarea-footer">
                                <div class="char-count">
                                    <span class="current">0</span>/<span class="max">500</span> caractères
                                </div>
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Publier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endauth
            
                @if(isset($ressource->discussions) && $ressource->discussions->count() > 0)
                <div class="discussions-container">
                    @foreach($ressource->discussions->take(5) as $comment)
                    <div class="comment-item {{ $loop->first ? 'new' : '' }}">
                        <div class="comment-header">
                            <div class="user-info user-details">
                                <div class="user-name">
                                    <img class="user-avatar" src="{{ $comment->user->img ? asset('storage/user/' . $comment->user->img) : asset('img/default-user.png') }}" alt="user">
                                    {{ $comment->user->prenom ?? 'Utilisateur' }} {{ $comment->user->nom ?? '' }}
                                </div>
                                <div class="comment-time">
                                    <i class="far fa-clock"></i>
                                    {{ $comment->created_at->diffForHumans() }}
                                </div>
                            </div>
            
                            @if(auth()->user() && 
                                (auth()->user()->role === 'admin' ||
                                 auth()->user()->id === $comment->user_id ||
                                 (auth()->user()->role === 'responsable' &&
                                  $ressource->categorie &&
                                  $ressource->categorie->user_id == auth()->id())))
                            <div class="comment-actions">
                                <button class="btn-actions" onclick="toggleCommentActions({{ $comment->id_discussion }})">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="actions-dropdown" id="commentActions{{ $comment->id_discussion }}">
                                    @if(auth()->user()->role === 'admin' ||
                                       (auth()->user()->role === 'responsable' &&
                                        $ressource->categorie &&
                                        $ressource->categorie->user_id == auth()->id()))
                                    <form action="{{ route('discussions.moderate', $comment->id_discussion) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-eye-slash"></i>
                                            Modérer le commentaire
                                        </button>
                                    </form>
                                    @endif
                                    @if(auth()->user()->role === 'admin' ||
                                       auth()->user()->id === $comment->user_id ||
                                       (auth()->user()->role === 'responsable' &&
                                        $ressource->categorie &&
                                        $ressource->categorie->user_id == auth()->id()))
                                    <form action="{{ route('discussions.delete', $comment->id_discussion) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item delete" onclick="return confirm('Supprimer ce commentaire ?')">
                                            <i class="fas fa-trash"></i>
                                            Supprimer
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
            
                        <div class="comment-body">
                            <div class="comment-text">
                                {{ $comment->message }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            
                @if($ressource->discussions->count() > 5)
                <div class="load-more">
                    <a href="{{ route('discussions.index', $ressource->id_ressource) }}" class="load-more-btn">
                        <i class="fas fa-comments"></i>
                        Voir toutes les discussions ({{ $ressource->discussions->count() }})
                    </a>
                </div>
                @endif
            
                @else
                <!-- Empty State -->
                <div class="discussions-empty">
                    <div class="discussions-empty-icon">
                        <i class="fas fa-comment-slash"></i>
                    </div>
                    <h3 class="discussions-empty-title">Aucune discussion</h3>
                    <p class="discussions-empty-description">
                        Soyez le premier à partager vos questions, suggestions ou retours sur cette ressource.
                        Les discussions aident à améliorer l'expérience de tous les utilisateurs.
                    </p>
                    @auth
                    <a href="{{ route('discussions.index', $ressource->id_ressource) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i>
                        Démarrer une discussion
                    </a>
                    @else
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Se connecter pour commenter
                        </a>
                    </div>
                    @endauth
                </div>
                @endif
            </div>
            
                        <!-- History Tab -->
            @if(auth()->user()->canManageRessource($ressource))
            <div class="tab-pane" id="history">
                @if(isset($historique) && $historique->count() > 0)
                <div class="history-list">
                    @foreach($historique->take(10) as $event)
                    <div class="history-item">
                        <div class="history-icon">
                            @if($event->etat == 'active')
                            <i class="fas fa-play text-success"></i>
                            @elseif($event->etat == 'terminée')
                            <i class="fas fa-flag-checkered text-info"></i>
                            @else
                            <i class="fas fa-clock text-warning"></i>
                            @endif
                        </div>
                        <div class="history-content">
                            <div class="history-header">
                                @if($event->user)
                                <strong>{{ $event->user->prenom }} {{ $event->user->nom }}</strong>
                                @else
                                <strong>Système</strong>
                                @endif
                                <small>{{ $event->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="history-desc">
                                @if($event->reservation)
                                Réservation du {{ \Carbon\Carbon::parse($event->date_debut_utilisation)->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse($event->date_fin_utilisation)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h4>Aucun historique</h4>
                    <p>Aucun événement n'a été enregistré pour cette ressource.</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @else
    <!-- Section pour les non-connectés -->
    <div class="auth-required-section">
        <div class="empty-state">
            <i class="fas fa-lock"></i>
            <h4>Accès réservé</h4>
            <p>Connectez-vous pour accéder aux réservations et discussions.</p>
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Se connecter
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline">
                    Créer un compte
                </a>
            </div>
        </div>
    </div>
    @endauth
</div>

<style>
    /* ============================================
   STYLES SIMPLES ET MODERNES
============================================ */

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-back,
    .btn-edit {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-back {
        background: var(--surface);
        color: var(--text-secondary);
        border: 1px solid var(--border);
    }

    .btn-back:hover {
        background: var(--surface-hover);
        color: var(--text-main);
    }

    .btn-edit {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-edit:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Status Container */
    .status-container {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-disponible {
        background: var(--success-light);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .status-occupé {
        background: var(--warning-light);
        color: var(--warning-dark);
        border: 1px solid var(--warning);
    }

    .status-maintenance {
        background: var(--danger-light);
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .status-inactif {
        background: var(--secondary-light);
        color: var(--secondary-dark);
        border: 1px solid var(--secondary);
    }

    .category-badge {
        padding: 0.5rem 1rem;
        background: var(--primary-super-light);
        color: var(--primary);
        border-radius: 20px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Alerts */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid transparent;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-success {
        background: var(--success-light);
        border-color: var(--success);
        color: var(--success);
    }

    .alert-danger {
        background: var(--danger-light);
        border-color: var(--danger);
        color: var(--danger);
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }


    /* Info Cards */
    .info-card,
    .action-card,
    .meta-card,
    .stats-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .card-header {
        padding: 1.25rem;
        background: var(--surface-hover);
        border-bottom: 1px solid var(--border);
    }

    .card-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Specifications Grid */
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--surface-hover);
        border-radius: 8px;
    }

    .spec-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--primary-super-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .spec-info {
        display: flex;
        flex-direction: column;
    }

    .spec-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spec-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Resource Image */
    .resource-image {
        width: 100%;
        border-radius: 8px;
        height: 250px;
        object-fit: cover;
    }

    /* Actions Grid */
    .actions-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 8px;
        font-weight: 500;
        text-align: left;
        border: none;
        width: 100%;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .reserve-btn {
        background: var(--success-light);
        color: var(--success);
        border: 1px solid var(--success);
    }

    .reserve-btn:hover {
        background: var(--success);
        color: white;
    }

    .edit-btn {
        background: var(--primary-super-light);
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .edit-btn:hover {
        background: var(--primary);
        color: white;
    }

    .delete-btn {
        background: var(--danger-light);
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .delete-btn:hover {
        background: var(--danger);
        color: white;
    }

    .login-btn {
        background: var(--primary);
        color: white;
        border: 1px solid var(--primary);
    }

    .login-btn:hover {
        background: var(--primary-dark);
    }

    /* Status Actions */
    .status-actions {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .status-actions small {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .status-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .status-form {
        display: inline;
    }

    .status-btn {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .status-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .status-btn.available.active,
    .status-btn.available:hover {
        background: var(--success-light);
        color: var(--success);
        border-color: var(--success);
    }

    .status-btn.occupied.active,
    .status-btn.occupied:hover {
        background: var(--warning-light);
        color: var(--warning-dark);
        border-color: var(--warning);
    }

    .status-btn.maintenance.active,
    .status-btn.maintenance:hover {
        background: var(--danger-light);
        color: var(--danger);
        border-color: var(--danger);
    }

    /* Metadata List */
    .meta-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.5rem;
    }

    .meta-item i {
        color: var(--primary);
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
    }

    .meta-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .meta-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-main);
    }

    /* Statistics */
    .stats-list {
        display: flex;
        justify-content: space-around;
        text-align: center;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    /* Tabs Section */
    .tabs-section {
        background: var(--surface);
        border-radius: 10px;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        border-bottom: 1px solid var(--border);
        background: var(--surface-hover);
    }

    .tab-btn {
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 2px solid transparent;
    }

    .tab-btn:hover {
        color: var(--text-main);
        background: var(--surface);
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: var(--surface);
    }

    .tab-btn .badge {
        background: var(--primary);
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 0.75rem;
    }

    .tabs-content {
        padding: 1.5rem;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* Reservations List */
    .reservations-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .reservation-item {
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
    }

    .reservation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-super-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-details strong {
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .user-details small {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .reservation-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .reservation-dates {
        display: flex;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .date-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-main);
    }

    .date-item i {
        color: var(--primary);
    }

    .reservation-comment {
        font-size: 0.9rem;
        color: var(--text-secondary);
        padding: 0.75rem;
        background: var(--surface-hover);
        border-radius: 6px;
        margin-bottom: 0.75rem;
    }

    .reservation-actions {
        display: flex;
        justify-content: flex-end;
    }

    /* Discussions List */
    .discussions-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .comment-item {
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-icon:hover {
        background: var(--surface-hover);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0.5rem;
        min-width: 150px;
        display: none;
        z-index: 100;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        display: block;
        padding: 0.5rem 0.75rem;
        color: var(--text-main);
        text-decoration: none;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        border-radius: 4px;
    }

    .dropdown-item:hover {
        background: var(--surface-hover);
    }

    .dropdown-item.text-danger {
        color: var(--danger);
    }

    .comment-body {
        font-size: 0.95rem;
        color: var(--text-main);
        line-height: 1.5;
    }

    /* History List */
    .history-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .history-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
    }

    .history-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--surface-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .history-content {
        flex: 1;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .history-header strong {
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .history-header small {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .history-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--primary-light);
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--text-main);
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .auth-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
    }

    /* Auth Required Section */
    .auth-required-section {
        margin-top: 2rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }

    /* ====================== Discussions ================================== */
    /* Discussions Container */
    .discussions-container {
        background: var(--vibrant-bg-light);
        border-radius: var(--radius-xl);
        border: 2px solid var(--vibrant-border);
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(67, 97, 238, 0.1);
    }
    
    /* Comment Item - Modern Card Design */
    .comment-item {
        background: var(--vibrant-bg-card);
        border: 1px solid transparent;
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-md);
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
    }
    
    .comment-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--vibrant-gradient);
        opacity: 0;
        transition: opacity var(--transition-normal);
    }
    
    .comment-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.15);
        border-color: var(--vibrant-border);
    }
    
    .comment-item:hover::before {
        opacity: 1;
    }
    
    /* Comment Header */
    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-md);
        padding-bottom: var(--space-sm);
        border-bottom: 1px solid var(--vibrant-border);
    }
    
    /* User Info with Avatar */
    .user-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-full);
        background: var(--vibrant-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        transition: transform var(--transition-normal);
    }
    
    .user-avatar:hover {
        transform: scale(1.1);
    }
    
    .user-details {
        display: flex;
        flex-direction: column;
    }
    
    .user-name {
        font-weight: 600;
        color: var(--vibrant-primary);
        font-size: var(--font-size-lg);
        margin-bottom: var(--space-xs);
    }
    
    .comment-time {
        color: var(--vibrant-text-light);
        font-size: var(--font-size-sm);
        display: flex;
        align-items: center;
        gap: var(--space-xs);
    }
    
    .comment-time i {
        color: var(--vibrant-primary-light);
    }
    
    /* Comment Actions */
    .comment-actions {
        position: relative;
    }
    
    .btn-actions {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-md);
        border: 2px solid var(--vibrant-border);
        background: var(--vibrant-bg-light);
        color: var(--vibrant-primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .btn-actions:hover {
        background: var(--vibrant-primary);
        color: white;
        border-color: var(--vibrant-primary);
        transform: rotate(90deg);
    }
    
    .actions-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: var(--vibrant-bg-card);
        border: 2px solid var(--vibrant-border);
        border-radius: var(--radius-md);
        padding: var(--space-sm);
        min-width: 180px;
        display: none;
        z-index: 100;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
        animation: slideDown 0.2s ease-out;
    }
    
    .actions-dropdown.show {
        display: block;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        color: var(--vibrant-text);
        text-decoration: none;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        border-radius: var(--radius-sm);
        transition: all var(--transition-fast);
    }
    
    .dropdown-item:hover {
        background: var(--vibrant-bg-light);
        color: var(--vibrant-primary);
        transform: translateX(5px);
    }
    
    .dropdown-item.delete:hover {
        color: var(--danger);
        background: var(--danger-light);
    }
    
    /* Comment Body */
    .comment-body {
        margin-bottom: var(--space-md);
    }
    
    .comment-text {
        color: var(--vibrant-text);
        font-size: var(--font-size-base);
        line-height: 1.6;
        padding: var(--space-md);
        background: var(--vibrant-bg-light);
        border-radius: var(--radius-md);
        border-left: 4px solid var(--vibrant-primary-light);
        position: relative;
    }
    
    .comment-text::before {
        content: '"';
        position: absolute;
        top: -10px;
        left: 10px;
        font-size: 3rem;
        color: var(--vibrant-primary-light);
        opacity: 0.3;
        font-family: serif;
    }
    
    /* Empty Discussions State */
    .discussions-empty {
        text-align: center;
        padding: var(--space-2xl) var(--space-xl);
        background: linear-gradient(135deg, var(--vibrant-bg-light) 0%, white 100%);
        border-radius: var(--radius-xl);
        border: 2px dashed var(--vibrant-border);
    }
    
    .discussions-empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto var(--space-lg);
        background: var(--vibrant-gradient);
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .discussions-empty-title {
        color: var(--vibrant-primary);
        font-size: var(--font-size-2xl);
        margin-bottom: var(--space-md);
        font-weight: 700;
    }
    
    .discussions-empty-description {
        color: var(--vibrant-text-light);
        font-size: var(--font-size-base);
        margin-bottom: var(--space-xl);
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }
    
    /* New Discussion Form */
    .new-discussion-form {
        background: var(--vibrant-bg-card);
        border: 2px solid var(--vibrant-border);
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-xl);
        box-shadow: 0 10px 40px rgba(67, 97, 238, 0.1);
    }
    
    .form-title {
        color: var(--vibrant-primary);
        font-size: var(--font-size-xl);
        margin-bottom: var(--space-lg);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .form-title i {
        color: var(--vibrant-primary-light);
    }
    
    .discussion-input-container {
        position: relative;
    }
    
    .discussion-textarea {
        width: 100%;
        padding: var(--space-lg);
        border: 2px solid var(--vibrant-border);
        border-radius: var(--radius-lg);
        background: var(--vibrant-bg-light);
        color: var(--vibrant-text);
        font-size: var(--font-size-base);
        line-height: 1.6;
        transition: all var(--transition-normal);
        resize: vertical;
        min-height: 120px;
        font-family: var(--font-family);
    }
    
    .discussion-textarea:focus {
        outline: none;
        border-color: var(--vibrant-primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }
    
    .textarea-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--space-sm);
    }
    
    .char-count {
        color: var(--vibrant-text-light);
        font-size: var(--font-size-sm);
    }
    
    .char-count.warning {
        color: var(--warning);
        font-weight: 600;
    }
    
    .char-count.error {
        color: var(--danger);
        font-weight: 700;
    }
    
    .submit-btn {
        padding: var(--space-sm) var(--space-xl);
        background: var(--vibrant-gradient);
        color: white;
        border: none;
        border-radius: var(--radius-full);
        font-weight: 600;
        font-size: var(--font-size-base);
        cursor: pointer;
        transition: all var(--transition-normal);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    }
    
    .submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
    
    /* Discussion Categories */
    .discussion-categories {
        display: flex;
        gap: var(--space-sm);
        margin-bottom: var(--space-lg);
        flex-wrap: wrap;
    }
    
    .category-tag {
        padding: var(--space-xs) var(--space-md);
        background: var(--vibrant-bg-light);
        border: 1px solid var(--vibrant-border);
        border-radius: var(--radius-full);
        color: var(--vibrant-text-light);
        font-size: var(--font-size-sm);
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    
    .category-tag:hover {
        background: var(--vibrant-primary-light);
        color: white;
        transform: translateY(-2px);
    }
    
    .category-tag.active {
        background: var(--vibrant-gradient);
        color: white;
        border-color: transparent;
    }
    
    /* Discussion Load More */
    .load-more {
        text-align: center;
        margin-top: var(--space-xl);
    }
    
    .load-more-btn {
        padding: var(--space-sm) var(--space-xl);
        background: transparent;
        border: 2px solid var(--vibrant-primary);
        color: var(--vibrant-primary);
        border-radius: var(--radius-full);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-normal);
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .load-more-btn:hover {
        background: var(--vibrant-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    }
    
    /* Animation for new comments */
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
    
    .comment-item.new {
        animation: fadeInUp 0.5s ease-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Switching
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Update active tab button
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Show active tab pane
                tabPanes.forEach(pane => {
                    pane.classList.remove('active');
                    if (pane.id === tabId) {
                        pane.classList.add('active');
                    }
                });
            });
        });

        // Toggle comment actions dropdown
        window.toggleCommentActions = function(commentId) {
            const menu = document.getElementById('commentActions' + commentId);
            menu.classList.toggle('show');

            // Close other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                if (otherMenu.id !== 'commentActions' + commentId) {
                    otherMenu.classList.remove('show');
                }
            });
        };

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.comment-actions')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Confirmation for status change
        const statusForms = document.querySelectorAll('.status-form');
        statusForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const status = this.querySelector('input[name="statut"]').value;
                const statusNames = {
                    'disponible': 'Disponible',
                    'occupé': 'Occupé',
                    'maintenance': 'Maintenance',
                    'inactif': 'Inactif'
                };

                if (!confirm(`Changer le statut en "${statusNames[status]}" ?`)) {
                    e.preventDefault();
                }
            });
        });

        // Animation for cards
        const cards = document.querySelectorAll('.info-card, .action-card, .meta-card, .stats-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection