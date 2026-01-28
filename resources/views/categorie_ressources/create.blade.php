<!-- resources/views/categorie_ressources/create.blade.php -->
@extends('layouts.app')

@section('title', 'Créer une Catégorie - DataCenterPro')

@section('content')
<div class="main-container">
    <!-- En-tête de page -->
    <div class="page-header">
        <div>
            <h1 class="page-title text-gradient">
                <i class="fas fa-plus-circle me-2"></i>
                Nouvelle Catégorie
            </h1>
            <p class="page-subtitle">Créez une nouvelle catégorie pour organiser vos ressources</p>
        </div>

        <div class="page-actions">
            <a href="{{ route('categorie_ressources.index') }}" class="btn btn-outline btn-vibrant">
                <i class="fas fa-arrow-left me-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="card card-vibrant">
        <div class="form-headerX bg-vibrant">
            <h3 class="card-title">
                <i class="fas fa-pen me-2"></i>
                Informations de la catégorie
            </h3>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('categorie_ressources.store') }}" enctype="multipart/form-data" id="createCategoryForm">
                @csrf

                @if($errors->any())
                <div class="alert alert-danger alert-vibrant">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4 class="alert-title">Veuillez corriger les erreurs suivantes :</h4>
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
                        <i class="fas fa-tag me-2"></i>
                        Nom de la catégorie
                    </label>
                    <div class="input-container">
                        <input type="text"
                            name="nom"
                            id="nom"
                            class="form-control form-control-vibrant @error('nom') is-invalid @enderror"
                            value="{{ old('nom') }}"
                            placeholder="Ex: Serveurs Physiques, Machines Virtuelles..."
                            required
                            maxlength="255">
                        <div class="input-icon-right">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                    @error('nom')
                    <div class="form-error form-error-vibrant">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group form-group-vibrant">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left me-2"></i>
                        Description
                    </label>
                    <div class="textarea-container">
                        <textarea name="description"
                            id="description"
                            class="form-control form-control-vibrant @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Décrivez cette catégorie...">{{ old('description') }}</textarea>
                        <div class="textarea-counter">
                            <span id="charCount">0</span>/1000
                        </div>
                    </div>
                    @error('description')
                    <div class="form-error form-error-vibrant">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Image -->
                <div class="form-group form-group-vibrant">
                    <label class="form-label">
                        <i class="fas fa-image me-2"></i>
                        Image de la catégorie
                    </label>

                    <div class="image-upload-vibrant" id="imageUploadArea">
                        <div id="uploadPlaceholder" class="upload-placeholder-vibrant">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h4>Ajouter une image</h4>
                            <p class="upload-hint">Glissez-déposez ou cliquez pour télécharger</p>
                            <p class="upload-formats">Formats supportés : JPG, PNG, GIF, SVG • Max 2 Mo</p>
                        </div>
                        <div id="imagePreview" class="image-preview-vibrant d-none">
                            <img id="previewImage" src="" alt="Aperçu" class="preview-image-vibrant">
                            <button type="button" class="btn-remove-preview-vibrant" id="removePreview">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="file"
                            name="img"
                            id="img"
                            class="image-input-vibrant"
                            accept="image/*">
                    </div>
                    @error('img')
                    <div class="form-error form-error-vibrant mt-2">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="form-group">
                        <label for="user_id">Choisir le superviseur :</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">-- Sélectionner --</option>
                            @foreach($users as $user)
                           <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nom }} {{ $user->prenom }}</option>

                            @endforeach
                        </select>
                        @error('user_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <!-- Conseils -->
                <div class="tips-box">
                    <div class="tips-header">
                        <i class="fas fa-lightbulb"></i>
                        <h4>Conseils pour une bonne catégorie</h4>
                    </div>
                    <div class="tips-content">
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="tip-text">
                                <h5>Nom unique et descriptif</h5>
                                <p>Choisissez un nom clair qui décrit bien le contenu</p>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="tip-text">
                                <h5>Description utile</h5>
                                <p>Ajoutez une description qui aide à comprendre l'usage</p>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="tip-text">
                                <h5>Image représentative</h5>
                                <p>Une image pertinente aide à identifier rapidement</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="form-actions-vibrant">
                    <a href="{{ route('categorie_ressources.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-create">
                        <i class="fas fa-plus me-2"></i>
                        Créer la catégorie
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Exemples -->
    <div class="examples-section">
        <h3 class="section-title">
            <i class="fas fa-star me-2"></i>
            Exemples inspirants
        </h3>
        <div class="examples-grid">
            <div class="example-card example-primary">
                <div class="example-icon">
                    <i class="fas fa-server"></i>
                </div>
                <div class="example-content">
                    <h4>Serveurs Physiques</h4>
                    <p>Infrastructure matérielle, racks, unités de calcul</p>
                </div>
            </div>

            <div class="example-card example-success">
                <div class="example-icon">
                    <i class="fas fa-cloud"></i>
                </div>
                <div class="example-content">
                    <h4>Cloud & Virtuel</h4>
                    <p>VM, conteneurs, services cloud, instances</p>
                </div>
            </div>

            <div class="example-card example-warning">
                <div class="example-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="example-content">
                    <h4>Stockage & Données</h4>
                    <p>NAS, SAN, bases de données, sauvegardes</p>
                </div>
            </div>

            <div class="example-card example-info">
                <div class="example-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="example-content">
                    <h4>Réseau & Connexion</h4>
                    <p>Routeurs, switches, firewalls, câblage</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* === UPLOAD D'IMAGE VIVANT === */
    .image-upload-vibrant {
        border: 3px dashed var(--vibrant-border);
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        background: var(--vibrant-bg-light);
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-upload-vibrant:hover {
        border-color: var(--vibrant-primary);
        background: linear-gradient(135deg, #f8f9ff, #eef2ff);
        transform: translateY(-3px);
    }

    .upload-placeholder-vibrant {
        color: var(--vibrant-primary);
    }

    .upload-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--vibrant-primary-light);
    }

    .upload-placeholder-vibrant h4 {
        color: var(--vibrant-primary);
        margin-bottom: 0.5rem;
    }

    .upload-hint {
        color: var(--vibrant-primary-light);
        margin-bottom: 0.25rem;
    }

    .upload-formats {
        color: var(--vibrant-text-light);
        font-size: 0.9rem;
    }

    .image-preview-vibrant {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }

    .preview-image-vibrant {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-remove-preview-vibrant {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--vibrant-warning);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-remove-preview-vibrant:hover {
        background: #d63384;
        transform: scale(1.1);
    }

    .image-input-vibrant {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* === BOÎTE DE CONSEILS === */
    .tips-box {
        background: linear-gradient(135deg, #eef2ff, #f0f9ff);
        border-radius: 16px;
        padding: 1.5rem;
        margin: 2rem 0;
        border: 2px solid #e0e7ff;
    }

    .tips-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .tips-header i {
        color: var(--vibrant-warning);
        font-size: 1.5rem;
    }

    .tips-header h4 {
        color: var(--vibrant-primary);
        margin: 0;
    }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e0e7ff;
    }

    .tip-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .tip-icon {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--vibrant-success);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .tip-text h5 {
        color: var(--vibrant-primary);
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
    }

    .tip-text p {
        color: var(--vibrant-text-light);
        margin: 0;
        font-size: 0.9rem;
    }

    /* === ACTIONS DU FORMULAIRE === */
    .form-actions-vibrant {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--vibrant-border);
    }

    .btn-cancel {
        background: transparent;
        color: var(--vibrant-text-light);
        border: 2px solid var(--vibrant-border);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background: var(--vibrant-bg-light);
        color: var(--vibrant-primary);
        border-color: var(--vibrant-primary);
        transform: translateY(-2px);
    }

    .btn-create {
        background: var(--vibrant-gradient);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn-create:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
    }

    .btn-create:active {
        transform: translateY(-1px);
    }

    /* === SECTION EXEMPLES === */
    .examples-section {
        margin-top: 3rem;
        padding: 2rem 0;
    }

    .section-title {
        color: var(--vibrant-primary);
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }

    .examples-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .example-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .example-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .example-primary {
        border-color: #4361ee;
        border-left: 6px solid #4361ee;
    }

    .example-success {
        border-color: #4cc9f0;
        border-left: 6px solid #4cc9f0;
    }

    .example-warning {
        border-color: #f72585;
        border-left: 6px solid #f72585;
    }

    .example-info {
        border-color: #560bad;
        border-left: 6px solid #560bad;
    }

    .example-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .example-primary .example-icon {
        background: var(--vibrant-gradient);
    }

    .example-success .example-icon {
        background: linear-gradient(135deg, #4cc9f0, #4895ef);
    }

    .example-warning .example-icon {
        background: linear-gradient(135deg, #f72585, #7209b7);
    }

    .example-info .example-icon {
        background: linear-gradient(135deg, #560bad, #3a0ca3);
    }

    .example-content h4 {
        color: var(--vibrant-text);
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
    }

    .example-content p {
        color: var(--vibrant-text-light);
        margin: 0;
        font-size: 0.9rem;
    }

    /* === BOUTONS VIBRANTS === */
    .btn-vibrant {
        background: transparent;
        color: var(--vibrant-primary);
        border: 2px solid var(--vibrant-primary);
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-vibrant:hover {
        background: var(--vibrant-primary);
        color: white;
        transform: translateY(-2px);
    }

    /* === MESSAGES D'ERREUR VIBRANTS === */
    .form-error-vibrant {
        background: linear-gradient(135deg, #ffeef0, #fff0f3);
        color: var(--vibrant-warning);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-top: 0.5rem;
        border-left: 4px solid var(--vibrant-warning);
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    .alert-vibrant {
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #fff0f3, #ffeef0);
        border-left: 6px solid var(--vibrant-warning);
    }

    /* === ANIMATIONS === */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .btn-create.loading {
        position: relative;
        color: transparent;
    }

    .btn-create.loading:after {
        content: '';
        position: absolute;
        width: 24px;
        height: 24px;
        top: 50%;
        left: 50%;
        margin: -12px 0 0 -12px;
        border: 3px solid white;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .examples-grid {
            grid-template-columns: 1fr;
        }

        .form-actions-vibrant {
            flex-direction: column;
        }

        .form-actions-vibrant a,
        .form-actions-vibrant button {
            width: 100%;
            justify-content: center;
        }

        .image-upload-vibrant {
            padding: 2rem 1rem;
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

            // Animation du compteur
            charCount.style.transform = 'scale(1.2)';
            setTimeout(() => {
                charCount.style.transform = 'scale(1)';
            }, 200);
        });

        // Initialiser le compteur
        charCount.textContent = description.value.length;

        // Gestion d'image
        const uploadArea = document.getElementById('imageUploadArea');
        const imgInput = document.getElementById('img');
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImage');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const removePreviewBtn = document.getElementById('removePreview');

        // Click sur zone d'upload
        uploadArea.addEventListener('click', function(e) {
            if (e.target !== removePreviewBtn) {
                imgInput.click();
            }
        });

        // Effet drag over
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = 'var(--vibrant-primary)';
            this.style.transform = 'scale(1.02)';
            this.style.animation = 'pulse 0.5s ease';
        });

        uploadArea.addEventListener('dragleave', function() {
            this.style.borderColor = '';
            this.style.transform = '';
            this.style.animation = '';
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '';
            this.style.transform = '';
            this.style.animation = '';

            if (e.dataTransfer.files.length) {
                imgInput.files = e.dataTransfer.files;
                handleImageChange();
            }
        });

        // Changement de fichier
        imgInput.addEventListener('change', handleImageChange);

        function handleImageChange() {
            if (imgInput.files && imgInput.files[0]) {
                const file = imgInput.files[0];

                // Validation taille
                if (file.size > 2 * 1024 * 1024) {
                    showAlert('L\'image ne doit pas dépasser 2 Mo', 'error');
                    imgInput.value = '';
                    return;
                }

                // Aperçu
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    uploadPlaceholder.classList.add('d-none');
                    preview.classList.remove('d-none');

                    // Animation d'apparition
                    preview.style.opacity = '0';
                    preview.style.transform = 'scale(0.8)';

                    setTimeout(() => {
                        preview.style.opacity = '1';
                        preview.style.transform = 'scale(1)';
                        preview.style.transition = 'all 0.3s ease';
                    }, 10);
                }

                reader.readAsDataURL(file);
            }
        }

        // Supprimer l'aperçu
        if (removePreviewBtn) {
            removePreviewBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                imgInput.value = '';
                preview.classList.add('d-none');
                uploadPlaceholder.classList.remove('d-none');
            });
        }

        // Validation du formulaire
        const form = document.getElementById('createCategoryForm');
        const submitBtn = form.querySelector('.btn-create');

        form.addEventListener('submit', function(e) {
            const nom = document.getElementById('nom').value.trim();

            if (!nom) {
                e.preventDefault();
                showAlert('Le nom de la catégorie est obligatoire', 'error');
                document.getElementById('nom').focus();
                return false;
            }

            // Ajouter effet de chargement
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Création en cours...';

            return true;
        });

        // Focus automatique sur le champ nom
        document.getElementById('nom').focus();

        // Fonction pour afficher des alertes
        function showAlert(message, type = 'info') {
            // Créer l'alerte
            const alert = document.createElement('div');
            alert.className = `alert alert-${type === 'error' ? 'danger' : 'info'} alert-vibrant`;
            alert.style.position = 'fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '10000';
            alert.style.minWidth = '300px';
            alert.style.boxShadow = '0 5px 20px rgba(0,0,0,0.2)';
            alert.style.animation = 'slideIn 0.3s ease';

            alert.innerHTML = `
            <div class="alert-icon">
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            </div>
            <div class="alert-content">
                <p class="mb-0">${message}</p>
            </div>
            <button type="button" class="alert-dismiss" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

            document.body.appendChild(alert);

            // Auto-suppression après 5 secondes
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        // Ajouter les animations CSS
        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    `;
        document.head.appendChild(style);
    });
</script>
@endsection