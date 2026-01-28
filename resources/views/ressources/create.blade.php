@extends('layouts.app')

@section('content')
@auth
@if(!in_array(auth()->user()->role, ['admin', 'responsable']))
<div class="alert alert-danger">
    <i class="fas fa-ban"></i> Vous n'avez pas la permission de créer des ressources.
</div>
<script>
    setTimeout(() => {
        window.location.href = "{{ route('ressources.index') }}";
    }, 3000);
</script>
@php return; @endphp
@endif
@endauth

<div class="main-container">
    <!-- En-tête de page -->
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">Créer une Nouvelle Ressource</h1>
            <p class="page-subtitle">
                Ajoutez une nouvelle ressource au centre de données
                @if(auth()->user()->role === 'responsable')
                <br><small class="text-warning">Vous ne pouvez créer que dans vos catégories</small>
                @endif
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('ressources.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Carte du formulaire -->
            <div class="card">
                <div class="form-headerX">
                    <h2 class="form-headerX-title">
                        <i class="fas fa-plus-circle"></i> Formulaire de création
                    </h2>
                    <div class="card-subtitle">
                        Remplissez tous les champs obligatoires (<span style="color: red;">*</span>)
                    </div>
                </div>

                <form action="{{ route('ressources.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
                                                value="{{ old('nom') }}"
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
                                                value="{{ old('localisation') }}"
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
                                        placeholder="Décrivez la ressource, son utilité...">{{ old('description') }}</textarea>
                                    <span class="textarea-counter">
                                        <span id="charCount">0</span>/1000
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
                                            required>
                                            <option value="">Sélectionnez une catégorie</option>
                                            @foreach($categories as $category)
                                            @if(auth()->user()->role === 'admin' ||
                                            (auth()->user()->role === 'responsable' && $category->user_id == auth()->id()))
                                            <option value="{{ $category->id_categorie }}"
                                                {{ old('id_categorie') == $category->id_categorie ? 'selected' : '' }}>
                                                {{ $category->nom }}
                                                @if(auth()->user()->role === 'responsable')
                                                (Votre catégorie)
                                                @endif
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('id_categorie')
                                        <div class="form-feedback invalid">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                        @enderror
                                        @if(auth()->user()->role === 'responsable')
                                        <div class="form-feedback valid">
                                            <i class="fas fa-info-circle"></i> Vous ne pouvez créer que dans vos catégories
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-vibrant">
                                        <label for="statut" class="form-label required">
                                            <i class="fas fa-circle"></i> Statut initial
                                        </label>
                                        <select id="statut"
                                            name="statut"
                                            class="form-control-vibrant @error('statut') is-invalid @enderror"
                                            required>
                                            <option value="disponible" {{ old('statut') == 'disponible' ? 'selected' : '' }}>
                                                Disponible
                                            </option>
                                            <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>
                                                Inactif
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
                                                value="{{ old('cpu') }}"
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
                                                value="{{ old('ram') }}"
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
                                                value="{{ old('capacite_stockage') }}"
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
                                                value="{{ old('bande_passante') }}"
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
                                        value="{{ old('os') }}"
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

                        <!-- Image -->
                        <div class="form-section">
                            <h4 class="form-section-title">
                                <i class="fas fa-image"></i>
                                Image
                            </h4>

                            <div class="form-group-vibrant">
                                <label for="img" class="form-label">
                                    <i class="fas fa-camera"></i> Image de la ressource
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
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Réinitialiser
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('ressources.index') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer la ressource
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
    document.addEventListener('DOMContentLoaded', function() {

        /* ================================
           1️⃣ Compteur de caractères (max 1000)
        ================================= */
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        const MAX_LENGTH = 1000;

        if (descriptionTextarea && charCount) {

            // Met à jour le compteur et limite le texte à 1000 caractères
            const updateCharCount = () => {
                if (descriptionTextarea.value.length > MAX_LENGTH) {
                    descriptionTextarea.value =
                        descriptionTextarea.value.substring(0, MAX_LENGTH);
                }
                charCount.textContent = descriptionTextarea.value.length;
            };

            descriptionTextarea.addEventListener('input', updateCharCount);

            // Initialisation du compteur au chargement de la page
            updateCharCount();
        }

        /* ================================
           2️⃣ Vérification des permissions (responsable)
        ================================= */
        const categorySelect = document.getElementById('id_categorie');

        if (categorySelect && typeof userRole !== 'undefined' && userRole === 'responsable') {

            categorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                // Empêche le responsable de choisir une catégorie non autorisée
                if (
                    this.value !== '' &&
                    !selectedOption.textContent.includes('Votre catégorie')
                ) {
                    alert('⚠️ Vous ne pouvez créer des ressources que dans vos catégories.');
                    this.value = '';
                }
            });
        }

    });
</script>
@endpush

@endsection