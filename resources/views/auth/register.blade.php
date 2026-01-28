@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="register-container">
    <div class="register-wrapper">
        <!-- Section gauche avec branding -->
        <div class="register-brand-section">
            <div class="brand-content">
                <div class="brand-logo">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo DataCenterPro" width="80">
                    <h1>DataCenter<span class="brand-highlight">Pro</span></h1>
                </div>
                <h2 class="brand-tagline">Rejoignez la plateforme de gestion du Data Center</h2>
                <p class="brand-description">
                    Inscrivez-vous pour gérer vos demandes de réservation de serveurs, machines 
                    virtuelles, espaces de stockage et équipements réseau. Profitez d'un suivi 
                    détaillé, d'une gestion transparente des ressources et d'un système de 
                    notification intégré.
                </p>
            </div>
        </div>

        <!-- Section droite avec formulaire -->
        <div class="register-form-section">
            <div class="form-container">
                <!-- En-tête du formulaire -->
                <div class="form-header">
                    <h2>Créer un Compte</h2>
                    <p>Commencez votre expérience DataCenter Pro en quelques minutes</p>

                    <!-- Affichage des erreurs de validation -->
                    @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: var(--space-md);">
                        <div class="alert-icon">⚠</div>
                        <div class="alert-content">
                            <div class="alert-title">Veuillez corriger les erreurs suivantes :</div>
                            <ul class="error-list" style="margin: 0; padding-left: var(--space-md); font-size: var(--font-size-sm);">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Formulaire d'inscription -->
                <!-- Formulaire d'inscription -->
                <form method="POST" action="{{ route('register') }}" class="register-form" enctype="multipart/form-data">
                    @csrf

                    <!-- Grille pour nom et prénom -->
                    <div class="form-grid">
                        <!-- Champ Nom -->
                        <div class="form-group">
                            <div class="input-with-icon">
                                <input
                                    type="text"
                                    id="nom"
                                    name="nom"
                                    value="{{ old('nom') }}"
                                    required
                                    autofocus
                                    placeholder="Votre nom"
                                    class="form-control @error('nom') field-error @enderror">
                                <div class="input-icon">👤</div>
                            </div>
                            @error('nom')
                            <div class="error-message">
                                <span>⚠</span> {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Champ Prénom -->
                        <div class="form-group">
                            <div class="input-with-icon">
                                <input
                                    type="text"
                                    id="prenom"
                                    name="prenom"
                                    value="{{ old('prenom') }}"
                                    required
                                    placeholder="Votre prénom"
                                    class="form-control @error('prenom') field-error @enderror">
                                <div class="input-icon">👤</div>
                            </div>
                            @error('prenom')
                            <div class="error-message">
                                <span>⚠</span> {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Champ Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <span class="label-icon">📧</span>
                            Adresse Email
                            <span class="required-indicator">*</span>
                        </label>
                        <div class="input-with-icon">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="vous@exemple.com"
                                class="form-control @error('email') field-error @enderror">
                            <div class="input-icon">@</div>
                        </div>
                        @error('email')
                        <div class="error-message">
                            <span>⚠</span> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="form-group">
                        <label for="telephone" class="form-label">
                            <span class="label-icon">📱</span>
                            Téléphone
                            <span class="required-indicator">*</span>
                        </label>
                        <div class="input-with-icon">
                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="{{ old('telephone') }}"
                                required
                                placeholder="+33 1 23 45 67 89"
                                class="form-control @error('telephone') field-error @enderror">
                            <div class="input-icon">📱</div>
                        </div>
                        @error('telephone')
                        <div class="error-message">
                            <span>⚠</span> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Champ Image de profil -->
                    <div class="form-group image-upload-container">
                        <label class="form-label">
                            <span class="label-icon">🖼️</span>
                            Photo de profil (optionnel)
                        </label>

                        <div class="image-upload-wrapper">
                            <label for="img" class="image-upload-label">
                                <div class="image-preview" id="image-preview">
                                    <div class="image-preview-icon">📷</div>
                                    <div class="image-preview-text">Cliquez pour ajouter une photo</div>
                                    <img class="image-preview-img" id="image-preview-img" src="" alt="Aperçu de l'image">
                                    <div class="image-upload-remove" id="image-remove-btn" style="display: none;">×</div>
                                </div>
                                <input
                                    type="file"
                                    id="img"
                                    name="img"
                                    accept="image/*"
                                    class="image-upload-input @error('img') field-error @enderror">
                            </label>
                        </div>

                        <div class="image-upload-hint">
                            Formats acceptés : JPG, PNG, GIF • Taille max : 2MB
                        </div>

                        @error('img')
                        <div class="error-message">
                            <span>⚠</span> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Sélecteur de type d'utilisateur -->
                    <div class="user-type-selector">
                        <div class="type-option">
                            <input type="radio" id="type-ingenieur" name="user_type" value="Ingénieur" {{ old('user_type') == 'Ingénieur' ? 'checked' : '' }} required>
                            <label for="type-ingenieur" class="type-card">
                                <div class="type-icon">🔧</div>
                                <div class="type-name">Ingénieur</div>
                                <div class="type-description">Gestion technique et maintenance</div>
                            </label>
                        </div>

                        <div class="type-option">
                            <input type="radio" id="type-enseignant" name="user_type" value="Enseignant" {{ old('user_type') == 'Enseignant' ? 'checked' : '' }}>
                            <label for="type-enseignant" class="type-card">
                                <div class="type-icon">🎓</div>
                                <div class="type-name">Enseignant</div>
                                <div class="type-description">Recherche et formation</div>
                            </label>
                        </div>

                        <div class="type-option">
                            <input type="radio" id="type-doctorant" name="user_type" value="Doctorant" {{ old('user_type') == 'Doctorant' ? 'checked' : '' }}>
                            <label for="type-doctorant" class="type-card">
                                <div class="type-icon">🔬</div>
                                <div class="type-name">Doctorant</div>
                                <div class="type-description">Recherche avancée</div>
                            </label>
                        </div>
                    </div>
                    @error('user_type')
                    <div class="error-message" style="margin-top: -10px; margin-bottom: 20px;">
                        <span>⚠</span> {{ $message }}
                    </div>
                    @enderror

                    <!-- Champ Mot de passe -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <span class="label-icon">🔒</span>
                            Mot de passe
                            <span class="required-indicator">*</span>
                        </label>
                        <div class="input-with-icon">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="Minimum 6 caractères"
                                class="form-control @error('password') field-error @enderror">
                            <div class="input-icon">
                                <button type="button" class="toggle-password" data-target="password">
                                    👁
                                </button>
                            </div>
                        </div>
                        <div class="password-strength" id="password-strength">
                            <div class="strength-meter">
                                <div class="strength-fill"></div>
                            </div>
                            <div class="strength-text" id="strength-text">Force du mot de passe</div>
                        </div>
                        @error('password')
                        <div class="error-message">
                            <span>⚠</span> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Confirmation du mot de passe -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <span class="label-icon">🔒</span>
                            Confirmer le mot de passe
                            <span class="required-indicator">*</span>
                        </label>
                        <div class="input-with-icon">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                placeholder="Répétez votre mot de passe"
                                class="form-control">
                            <div class="input-icon">
                                <button type="button" class="toggle-password" data-target="password_confirmation">
                                    👁
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="terms-agreement">
                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required class="form-check-input">
                        </div>
                        <label for="terms" class="terms-text">
                            En créant un compte, vous acceptez nos
                            <a href="#" target="_blank">Conditions d'utilisation</a> et notre
                            <a href="#" target="_blank">Politique de confidentialité</a>.
                            Nous pouvons vous envoyer des notifications par email,
                            que vous pouvez désactiver à tout moment.
                        </label>
                    </div>

                    <!-- Bouton d'inscription -->
                    <button type="submit" class="btn btn-primary btn-register">
                        <span class="btn-icon">→</span>
                        Créer mon compte
                        <span class="btn-loading"></span>
                    </button>

                    <!-- Lien de connexion -->
                    <div class="login-link">
                        Vous avez déjà un compte ?
                        <a href="{{ route('login') }}">Se connecter</a>
                    </div>

                    <!-- Informations de sécurité -->
                    <div class="security-info">
                        <div class="security-icon">🛡️</div>
                        <div class="security-text">
                            <strong>Vos données sont sécurisées </strong>
                        </div>
                    </div>
                </form>

                <!-- Footer du formulaire -->
                <div class="form-footer">
                    <p>© {{ date('Y') }} DataCenterPro. Tous droits réservés.</p>
                    <div class="footer-links">
                        <a href="#">Politique de confidentialité</a>
                        <a href="#">Conditions d'utilisation</a>
                        <a href="#">Support technique</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '👁‍🗨';
        });
    });

    // Vérification de la force du mot de passe
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.querySelector('.strength-fill');
    const strengthText = document.getElementById('strength-text');
    const passwordStrength = document.getElementById('password-strength');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        // Longueur minimale
        if (password.length >= 6) strength += 25;

        // Présence de lettres minuscules et majuscules
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;

        // Présence de chiffres
        if (/[0-9]/.test(password)) strength += 25;

        // Présence de caractères spéciaux
        if (/[^A-Za-z0-9]/.test(password)) strength += 25;

        // Mise à jour de l'indicateur visuel
        strengthMeter.style.width = strength + '%';

        // Classes et texte selon la force
        passwordStrength.className = 'password-strength';
        if (strength === 0) {
            strengthText.textContent = 'Force du mot de passe';
        } else if (strength <= 25) {
            passwordStrength.classList.add('strength-weak');
            strengthText.textContent = 'Faible';
        } else if (strength <= 50) {
            passwordStrength.classList.add('strength-fair');
            strengthText.textContent = 'Moyen';
        } else if (strength <= 75) {
            passwordStrength.classList.add('strength-good');
            strengthText.textContent = 'Bon';
        } else {
            passwordStrength.classList.add('strength-strong');
            strengthText.textContent = 'Fort';
        }
    });

    // Vérification de la correspondance des mots de passe
    const confirmPasswordInput = document.getElementById('password_confirmation');

    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;

        if (confirmPassword && password !== confirmPassword) {
            this.style.borderColor = 'var(--danger)';
            this.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
        } else {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        }
    });

    // Animation du bouton d'inscription
    document.querySelector('.register-form').addEventListener('submit', function(e) {
        // Vérification des termes
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Veuillez accepter les conditions d\'utilisation pour continuer.');
            termsCheckbox.focus();
            return;
        }

        const submitBtn = this.querySelector('.btn-register');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // Simuler un délai de chargement
        setTimeout(() => {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }, 2000);
    });

    // Effet visuel sur les champs de formulaire
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });

    // Sélection automatique du type d'utilisateur si déjà choisi
    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    userTypeRadios.forEach(radio => {
        if (radio.checked) {
            radio.dispatchEvent(new Event('change'));
        }

        radio.addEventListener('change', function() {
            userTypeRadios.forEach(r => {
                r.nextElementSibling.style.transform = '';
                r.nextElementSibling.style.boxShadow = '';
            });

            if (this.checked) {
                this.nextElementSibling.style.transform = 'translateY(-2px)';
                this.nextElementSibling.style.boxShadow = 'var(--shadow-md)';
            }
        });
    });

    // Validation en temps réel des champs requis
    document.querySelectorAll('input[required]').forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('field-error');
            } else {
                this.classList.remove('field-error');
            }
        });
    });

    // Gestion de l'upload d'image
    const imageInput = document.getElementById('img');
    const imagePreview = document.getElementById('image-preview');
    const imagePreviewImg = document.getElementById('image-preview-img');
    const imageRemoveBtn = document.getElementById('image-remove-btn');

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            // Vérifier la taille du fichier (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximale : 2MB');
                this.value = '';
                return;
            }

            // Vérifier le type de fichier
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format de fichier non supporté. Formats acceptés : JPG, PNG, GIF');
                this.value = '';
                return;
            }

            // Afficher la prévisualisation
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreviewImg.src = e.target.result;
                imagePreview.classList.add('has-image');
                imageRemoveBtn.style.display = 'flex';
            };

            reader.readAsDataURL(file);
        }
    });

    // Bouton de suppression d'image
    imageRemoveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Réinitialiser l'input file
        imageInput.value = '';

        // Réinitialiser la prévisualisation
        imagePreviewImg.src = '';
        imagePreview.classList.remove('has-image');
        this.style.display = 'none';
    });

    // Empêcher la propagation du clic sur le bouton de suppression
    imageRemoveBtn.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>
@endsection