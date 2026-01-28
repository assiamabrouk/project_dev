{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users-cog"></i> Gestion des Utilisateurs
            </h1>
            <p class="mb-0">Gérez tous les utilisateurs du système</p>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres et Recherche</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nom, prénom, email...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-control" id="role" name="role">
                            <option value="">Tous les rôles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="responsable" {{ request('role') == 'responsable' ? 'selected' : '' }}>Responsable</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-control" id="statut" name="statut">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="user_type" class="form-label">Type</label>
                        <select class="form-control" id="user_type" name="user_type">
                            <option value="">Tous les types</option>
                            <option value="ingenieur" {{ request('user_type') == 'ingenieur' ? 'selected' : '' }}>Ingénieur</option>
                            <option value="enseignant" {{ request('user_type') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                            <option value="doctorant" {{ request('user_type') == 'doctorant' ? 'selected' : '' }}>Doctorant</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages de session -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tableau des utilisateurs -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Utilisateurs</h6>
            <div>
                <span class="badge bg-info">Total: {{ $users->total() }}</span>
                <span class="badge bg-success ms-2">Actifs: {{ $activeUsers }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Nom & Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Type</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Date Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <strong>{{ $user->prenom }} {{ $user->nom }}</strong>
                                           </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->telephone ?? 'Non renseigné' }}</td>
                                    <td>
                                        @if($user->user_type == 'ingenieur')
                                            <span class="badge bg-primary">Ingénieur</span>
                                        @elseif($user->user_type == 'enseignant')
                                            <span class="badge bg-info">Enseignant</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Doctorant</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif($user->role == 'responsable')
                                            <span class="badge bg-primary">Responsable</span>
                                        @else
                                            <span class="badge bg-success">Utilisateur</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->statut == 'actif')
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group" style="display:flex ; flex-direction: column;">    
                                            <!-- Changer statut -->
                                                <!-- Éditer -->
                                            <a href="{{ route('utilisateurs.edit', $user->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Éditer">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('users.toggle-status', $user->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $user->statut == 'actif' ? 'btn-secondary' : 'btn-success' }}"
                                                        title="{{ $user->statut == 'actif' ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas {{ $user->statut == 'actif' ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Supprimer -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $user->id }}"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal de suppression -->
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer l'utilisateur :</p>
                                                        <p><strong>{{ $user->prenom }} {{ $user->nom }}</strong> ({{ $user->email }})</p>
                                                        <p class="text-danger">Cette action est irréversible !</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('utilisateurs.destroy', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                    </div>
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucun utilisateur trouvé</h4>
                    <p class="text-muted">Aucun utilisateur ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('users.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Réinitialiser les filtres
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection