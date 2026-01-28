@extends('layouts.app')

@section('content')
@auth
    @if(auth()->user()->role === 'user' || 
       (auth()->user()->role === 'responsable' && 
        $ressource->categorie->user_id !== auth()->id()))
        <div class="alert alert-danger">
            <i class="fas fa-ban"></i> Vous n'avez pas la permission de modifier cette ressource.
        </div>
        <script>
            setTimeout(() => {
                window.location.href = "{{ route('ressources.show', $ressource) }}";
            }, 3000);
        </script>
        @php return; @endphp
    @endif
@endauth

<div class="main-container">
    <!-- En-tête de page -->
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">Modifier la Ressource</h1>
            <p class="page-subtitle">Mettez à jour les informations de la ressource</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('ressources.show', $ressource->id_ressource) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Retour aux détails
            </a>
            <a href="{{ route('ressources.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list"></i>
                Liste des ressources
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Carte du formulaire -->
            <div class="card">
                <div class="form-headerX">
                    <h2 class="form-headerX-title">
                        <i class="fas fa-edit"></i> Modification de : {{ $ressource->nom }}
                    </h2>
                    <div class="card-subtitle">
                        Catégorie : {{ $ressource->categorie->nom ?? 'Non catégorisé' }}
                    </div>
                </div>

                <form action="{{ route('ressources.update', $ressource->id_ressource) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Informations de base -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-info-circle"></i>
                                Informations de Base
                            </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="nom" class="form-label required">
                                            <i class="fas fa-tag"></i> Nom de la ressource
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="input-icon fas fa-tag"></i>
                                            <input type="text"
                                                   id="nom"
                                                   name="nom"
                                                   value="{{ old('nom', $ressource->nom) }}"
                                                   class="form-control-vibrant @error('nom') is-invalid @enderror"
                                                   placeholder="Ex: Serveur Web Principal"
                                                   required
                                                   maxlength="255">
                                        </div>
                                        @error('nom')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="localisation" class="form-label required">
                                            <i class="fas fa-map-marker-alt"></i> Localisation
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="input-icon fas fa-map-marker-alt"></i>
                                            <input type="text"
                                                   id="localisation"
                                                   name="localisation"
                                                   value="{{ old('localisation', $ressource->localisation) }}"
                                                   class="form-control-vibrant @error('localisation') is-invalid @enderror"
                                                   placeholder="Ex: Salle A, Rack 12"
                                                   required
                                                   maxlength="255">
                                        </div>
                                        @error('localisation')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-vibrant">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <div class="textarea-container">
                                    <textarea id="description"
                                              name="description"
                                              class="form-control-vibrant @error('description') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Décrivez la ressource, son utilité...">{{ old('description', $ressource->description) }}</textarea>
                                    <span class="textarea-counter">
                                        <span id="charCount">{{ strlen(old('description', $ressource->description)) }}</span>/1000
                                    </span>
                                </div>
                                @error('description')
                                    <div class="form-feedback invalid">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-sitemap"></i>
                                Classification
                            </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="id_categorie" class="form-label required">
                                            <i class="fas fa-folder"></i> Catégorie
                                        </label>
                                        <select id="id_categorie"
                                                name="id_categorie"
                                                class="form-control-vibrant @error('id_categorie') is-invalid @enderror"
                                                required
                                                {{ auth()->user()->role === 'responsable' ? 'disabled' : '' }}>
                                            <option value="">Sélectionnez une catégorie</option>
                                            @foreach($categories as $category)
                                                @if(auth()->user()->role === 'admin' || 
                                                   (auth()->user()->role === 'responsable' && $category->user_id == auth()->id()))
                                                    <option value="{{ $category->id_categorie }}"
                                                            {{ (old('id_categorie', $ressource->id_categorie) == $category->id_categorie) ? 'selected' : '' }}>
                                                        {{ $category->nom }}
                                                        @if(auth()->user()->role === 'responsable')
                                                            (Votre catégorie)
                                                        @endif
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if(auth()->user()->role === 'responsable')
                                            <input type="hidden" name="id_categorie" value="{{ $ressource->id_categorie }}">
                                        @endif
                                        @error('id_categorie')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="statut" class="form-label required">
                                            <i class="fas fa-circle"></i> Statut
                                        </label>
                                        <select id="statut"
                                                name="statut"
                                                class="form-control-vibrant @error('statut') is-invalid @enderror"
                                                required>
                                            <option value="disponible" {{ old('statut', $ressource->statut) == 'disponible' ? 'selected' : '' }}>
                                                Disponible
                                            </option>
                                            <option value="occupé" {{ old('statut', $ressource->statut) == 'occupé' ? 'selected' : '' }}>
                                                Occupé
                                            </option>
                                            <option value="maintenance" {{ old('statut', $ressource->statut) == 'maintenance' ? 'selected' : '' }}>
                                                En maintenance
                                            </option>
                                            <option value="inactif" {{ old('statut', $ressource->statut) == 'inactif' ? 'selected' : '' }}>
                                                Inactif
                                            </option>
                                            <option value="réservé" {{ old('statut', $ressource->statut) == 'réservé' ? 'selected' : '' }}>
                                                Réservé
                                            </option>
                                        </select>
                                        @error('statut')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spécifications techniques -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-microchip"></i>
                                Spécifications Techniques
                            </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="cpu" class="form-label required">
                                            <i class="fas fa-microchip"></i> CPU
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="input-icon fas fa-microchip"></i>
                                            <input type="text"
                                                   id="cpu"
                                                   name="cpu"
                                                   value="{{ old('cpu', $ressource->cpu) }}"
                                                   class="form-control-vibrant @error('cpu') is-invalid @enderror"
                                                   placeholder="Ex: Intel Xeon E5-2690 v4"
                                                   required
                                                   maxlength="50">
                                        </div>
                                        @error('cpu')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="ram" class="form-label required">
                                            <i class="fas fa-memory"></i> RAM
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="input-icon fas fa-memory"></i>
                                            <input type="text"
                                                   id="ram"
                                                   name="ram"
                                                   value="{{ old('ram', $ressource->ram) }}"
                                                   class="form-control-vibrant @error('ram') is-invalid @enderror"
                                                   placeholder="Ex: 64GB DDR4"
                                                   required
                                                   maxlength="50">
                                        </div>
                                        @error('ram')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="capacite_stockage" class="form-label required">
                                            <i class="fas fa-hdd"></i> Capacité de stockage
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="input-icon fas fa-hdd"></i>
                                            <input type="text"
                                                   id="capacite_stockage"
                                                   name="capacite_stockage"
                                                   value="{{ old('capacite_stockage', $ressource->capacite_stockage) }}"
                                                   class="form-control-vibrant @error('capacite_stockage') is-invalid @enderror"
                                                   placeholder="Ex: 2TB SSD"
                                                   required
                                                   maxlength="50">
                                        </div>
                                        @error('capacite_stockage')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="bande_passante" class="form-label required">
                                            <i class="fas fa-network-wired"></i> Bande passante
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="input-icon fas fa-network-wired"></i>
                                            <input type="text"
                                                   id="bande_passante"
                                                   name="bande_passante"
                                                   value="{{ old('bande_passante', $ressource->bande_passante) }}"
                                                   class="form-control-vibrant @error('bande_passante') is-invalid @enderror"
                                                   placeholder="Ex: 10 Gbps"
                                                   required
                                                   maxlength="50">
                                        </div>
                                        @error('bande_passante')
                                            <div class="form-feedback invalid">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-vibrant">
                                <label for="os" class="form-label required">
                                    <i class="fas fa-desktop"></i> Système d'exploitation
                                </label>
                                <div class="input-with-icon">
                                    <i class="input-icon fas fa-desktop"></i>
                                    <input type="text"
                                           id="os"
                                           name="os"
                                           value="{{ old('os', $ressource->os) }}"
                                           class="form-control-vibrant @error('os') is-invalid @enderror"
                                           placeholder="Ex: Ubuntu 22.04 LTS"
                                           required
                                           maxlength="100">
                                </div>
                                @error('os')
                                    <div class="form-feedback invalid">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image actuelle -->
                        @if($ressource->img)
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-image"></i>
                                Image actuelle
                            </h4>

                            <div class="mb-3">
                                <label class="form-label">Image actuelle :</label>
                                <div>
                                    <img src="{{ Storage::url($ressource->img) }}" 
                                         alt="Image de {{ $ressource->nom }}" 
                                         style="max-width: 200px; border-radius: 8px;">
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="remove_img" name="remove_img">
                                    <label class="form-check-label" for="remove_img">
                                        Supprimer l'image actuelle
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Nouvelle image -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Nouvelle Image
                            </h4>

                            <div class="form-group-vibrant">
                                <label for="img" class="form-label">
                                    <i class="fas fa-camera"></i> Nouvelle image
                                </label>
                                <input type="file"
                                       id="img"
                                       name="img"
                                       class="form-control-vibrant @error('img') is-invalid @enderror"
                                       accept="image/*">
                                @error('img')
                                    <div class="form-feedback invalid">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @else
                                    <div class="form-feedback valid">
                                        <i class="fas fa-info-circle"></i> Formats acceptés: JPEG, PNG, JPG, GIF, SVG (max 2MB)
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions du formulaire -->
                    <div class="card-footer">
                        <div>
                            <a href="{{ route('ressources.show', $ressource->id_ressource) }}"
                               class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                        <div>
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('ressources.destroy', $ressource->id_ressource) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger me-2">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Compteur de caractères pour la description
const descriptionTextarea = document.getElementById('description');
const charCount = document.getElementById('charCount');

if (descriptionTextarea && charCount) {
    descriptionTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;

        if (length > 1000) {
            this.value = this.value.substring(0, 1000);
            charCount.textContent = 1000;
        }
    });
}

// Avertissement pour les changements de statut
document.getElementById('statut').addEventListener('change', function() {
    const oldStatus = "{{ $ressource->statut }}";
    const newStatus = this.value;
    
    if (oldStatus !== newStatus) {
        let message = '';
        
        switch(newStatus) {
            case 'maintenance':
                message = '⚠️ Mise en maintenance :\n\n' +
                         '• La ressource sera indisponible pour les réservations\n' +
                         '• Les réservations futures seront annulées\n' +
                         '• Les utilisateurs seront notifiés';
                break;
            case 'inactif':
                message = '🔴 Passage en inactif :\n\n' +
                         '• La ressource sera désactivée\n' +
                         '• Aucune nouvelle réservation possible\n' +
                         '• Les réservations existantes seront affectées';
                break;
            case 'occupé':
                message = '⏳ Statut occupé :\n\n' +
                         '• La ressource n\'est plus disponible pour de nouvelles réservations\n' +
                         '• Les réservations existantes restent actives';
                break;
        }
        
        if (message) {
            alert(message);
        }
    }
});
</script>
@endpush
@endsection