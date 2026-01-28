<!-- resources/views/categorie_ressources/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Modifier la Catégorie - DataCenterPro')

@section('content')
<div class="main-container">
    <!-- En-tête de page -->
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">
                <i class="fas fa-edit me-2"></i>
                Modifier Catégorie
            </h1>
            <p class="page-subtitle">{{ $categorie->nom }}</p>
        </div>

        <div class="page-actions">
            <a href="{{ route('categorie_ressources.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire d'édition -->
    <div class="card card-vibrant">
        <div class="card-header bg-vibrant">
            <h3 class="card-title">Informations de la catégorie</h3>
            <p class="text-muted mb-0">ID: #{{ $categorie->id_categorie }}</p>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('categorie_ressources.update', $categorie->id_categorie) }}" enctype="multipart/form-data" id="editCategoryForm">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="alert alert-danger">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4 class="alert-title">Erreurs de validation</h4>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Nom -->
                <div class="form-group form-group-vibrant">
                    <label for="nom" class="form-label required">
                        <i class="fas fa-tag me-1"></i>
                        Nom de la Catégorie
                    </label>
                    <input type="text"
                        name="nom"
                        id="nom"
                        class="form-control @error('nom') is-invalid @enderror"
                        value="{{ old('nom', $categorie->nom) }}"
                        placeholder="Ex: Serveurs Physiques"
                        required
                        maxlength="255">
                    @error('nom')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group form-group-vibrant">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-1"></i>
                        Description
                    </label>
                    <textarea name="description"
                        id="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="4"
                        placeholder="Description de la catégorie...">{{ old('description', $categorie->description) }}</textarea>
                    @error('description')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                    <div class="text-muted text-right mt-1">
                        <span id="charCount">{{ strlen(old('description', $categorie->description)) }}</span>/1000 caractères
                    </div>
                </div>

                <!-- Responsable / Utilisateur -->
                @auth
                @if (Auth::user()->role == 'admin')
                <div class="form-group form-group-vibrant">
                    <label for="user_id" class="form-label required">
                        <i class="fas fa-user-cog me-1"></i>
                        Responsable de la catégorie
                    </label>
                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Sélectionnez un responsable --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $categorie->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->prenom }} {{ $user->nom }} ({{ $user->role }})
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                @endif
                @endauth

                <!-- Gestion d'image -->
                <div class="form-group form-group-vibrant">
                    <label class="form-label">
                        <i class="fas fa-image me-1"></i>
                        Image
                    </label>

                    <!-- Image actuelle -->
                    <div class="image-preview-current mb-3">
                        <p class="text-muted mb-2">Image actuelle:</p>
                        @if($categorie->img)
                        <div class="d-flex align-items-center gap-3 p-3 border rounded bg-light">
                            <img src="{{ asset('storage/' . $categorie->img) }}"
                                alt="{{ $categorie->nom }}"
                                class="img-current">
                            <div>
                                <p class="mb-1 text-sm">{{ basename($categorie->img) }}</p>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeImage">
                                    <i class="fas fa-trash me-1"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                        @else
                        <div class="p-3 border rounded bg-light text-center text-muted">
                            <i class="fas fa-image fa-2x mb-2"></i>
                            <p class="mb-0">Aucune image</p>
                        </div>
                        @endif
                    </div>

                    <!-- Nouvelle image -->
                    <div class="image-upload-new">
                        <p class="text-muted mb-2">Nouvelle image:</p>
                        <div class="border rounded p-3 bg-light text-center" id="imageUploadArea">
                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                                <p class="mb-1">Cliquez pour choisir une image</p>
                                <p class="text-sm text-muted">JPG, PNG, GIF, SVG • Max 2 Mo</p>
                            </div>
                            <div id="imagePreview" class="d-none">
                                <img id="previewImage" src="" alt="Aperçu" class="img-preview">
                                <button type="button" class="btn-close-preview" id="removePreview">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="file"
                                name="img"
                                id="img"
                                class="d-none"
                                accept="image/*">
                        </div>
                        @error('img')
                        <div class="form-error mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Informations -->
                <div class="alert alert-info">
                    <div class="alert-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4 class="alert-title">Informations</h4>
                        <div class="d-flex flex-wrap gap-4 mt-2">
                            <div>
                                <p class="mb-0 text-sm text-muted">Créée par</p>
                                <p class="mb-0">{{ $categorie->user->prenom }} {{ $categorie->user->nom }}</p>
                            </div>
                            <div>
                                <p class="mb-0 text-sm text-muted">Date création</p>
                                <p class="mb-0">{{ $categorie->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="mb-0 text-sm text-muted">Ressources</p>
                                <p class="mb-0">{{ $categorie->ressources->count() }} ressource(s)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <button type="button" class="btn btn-danger" id="deleteCategoryBtn">
                        <i class="fas fa-trash me-2"></i>
                        Supprimer
                    </button>

                    <div class="d-flex gap-2">
                        <a href="{{ route('categorie_ressources.index') }}" class="btn btn-outline">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>

            <!-- Formulaire de suppression -->
            <form id="deleteForm" action="{{ route('categorie_ressources.destroy', $categorie->id_categorie) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<style>
    /* Styles spécifiques */
    .me-1 {
        margin-right: 0.25rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .mt-1 {
        margin-top: 0.25rem;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .d-flex {
        display: flex;
    }

    .d-none {
        display: none;
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .align-items-center {
        align-items: center;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 1rem;
    }

    .gap-4 {
        gap: 1.5rem;
    }

    .text-sm {
        font-size: var(--font-size-sm);
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .border {
        border: 1px solid var(--border);
    }

    .border-top {
        border-top: 1px solid var(--border);
    }

    .rounded {
        border-radius: var(--radius-md);
    }

    .bg-light {
        background-color: var(--surface-hover);
    }

    .pt-3 {
        padding-top: 1rem;
    }

    .p-3 {
        padding: 1rem;
    }

    /* Image styles */
    .img-current {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: var(--radius-md);
        border: 2px solid var(--border);
    }

    #imageUploadArea {
        position: relative;
        min-height: 120px;
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    #imageUploadArea:hover {
        border-color: var(--primary);
        background-color: var(--primary-super-light);
    }

    .img-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: var(--radius-md);
        margin: 0 auto;
        display: block;
    }

    .btn-close-preview {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--danger);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: var(--font-size-xs);
    }

    .btn-close-preview:hover {
        background: var(--danger-dark);
    }

    /* Bouton chargement */
    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading:after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin: -10px 0 0 -10px;
        border: 2px solid var(--text-on-primary);
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Compteur de caractères
        const description = document.getElementById('description');
        const charCount = document.getElementById('charCount');

        description.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Gestion d'image
        const uploadArea = document.getElementById('imageUploadArea');
        const imgInput = document.getElementById('img');
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImage');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');

        // Click sur zone d'upload
        uploadArea.addEventListener('click', function() {
            imgInput.click();
        });

        // Changement de fichier
        imgInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    uploadPlaceholder.classList.add('d-none');
                    preview.classList.remove('d-none');
                }

                reader.readAsDataURL(file);
            }
        });

        // Supprimer l'aperçu
        const removePreviewBtn = document.getElementById('removePreview');
        if (removePreviewBtn) {
            removePreviewBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                imgInput.value = '';
                preview.classList.add('d-none');
                uploadPlaceholder.classList.remove('d-none');
            });
        }

        // Supprimer l'image actuelle
        const removeImageBtn = document.getElementById('removeImage');
        const removeImageFlag = document.getElementById('removeImageFlag');

        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                if (confirm('Supprimer l\'image actuelle ?')) {
                    this.innerHTML = '<i class="fas fa-check me-1"></i> Supprimée';
                    this.classList.remove('btn-outline-danger');
                    this.classList.add('btn-success');
                    this.disabled = true;
                    removeImageFlag.value = '1';

                    // Cacher l'image
                    const currentImg = document.querySelector('.img-current');
                    if (currentImg) currentImg.style.opacity = '0.3';
                }
            });
        }

        // Supprimer la catégorie
        const deleteBtn = document.getElementById('deleteCategoryBtn');
        const deleteForm = document.getElementById('deleteForm');

        deleteBtn.addEventListener('click', function() {
            if (confirm('Voulez-vous vraiment supprimer cette catégorie ?')) {
                if (confirm('Cette action est irréversible. Confirmez la suppression.')) {
                    deleteForm.submit();
                }
            }
        });

        // Validation du formulaire
        const form = document.getElementById('editCategoryForm');
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function(e) {
            const nom = document.getElementById('nom').value.trim();

            if (!nom) {
                e.preventDefault();
                alert('Le nom est obligatoire');
                document.getElementById('nom').focus();
                return false;
            }

            // Ajouter effet de chargement
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;

            return true;
        });
    });
</script>
@endsection