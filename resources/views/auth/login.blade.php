@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <!-- Section gauche avec branding et illustration -->
        <div class="login-brand-section brand-content">
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

            <!-- Illustration ou éléments visuels -->
            <div class="login-illustration">
                <div class="server-node">
                    <div class="node-core"></div>
                    <div class="node-connection"></div>
                </div>
                <div class="server-node node-secondary">
                    <div class="node-core"></div>
                    <div class="node-connection"></div>
                </div>
                <div class="server-node node-tertiary">
                    <div class="node-core"></div>
                </div>
            </div>
        </div>

        <!-- Section droite avec formulaire -->
        <div class="login-form-section">
            <div class="form-container">
                <!-- En-tête du formulaire -->
                <div class="form-header">
                    <h2>Connexion au Dashboard</h2>
                    <p>Accédez à votre espace d'administration</p>

                    <!-- Affichage des erreurs de session -->
                    @if(session('status'))
                    <div class="alert alert-success">
                        <div class="alert-icon">✓</div>
                        <div class="alert-content">
                            {{ session('status') }}
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <div class="alert-icon">⚠</div>
                        <div class="alert-content">
                            <div class="alert-title">Erreur d'authentification</div>
                            <ul class="error-list">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Formulaire de connexion -->
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

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
                                autofocus
                                autocomplete="email"
                                placeholder="admin@votre-entreprise.com"
                                class="form-control">
                            <div class="input-icon">@</div>
                        </div>
                        @error('email')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

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
                                autocomplete="current-password"
                                placeholder="Votre mot de passe sécurisé"
                                class="form-control">
                            <div class="input-icon">
                                <button type="button" class="toggle-password" data-target="password">
                                    👁
                                </button>
                            </div>
                        </div>
                        @error('password')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Options supplémentaires -->
                    <div class="form-options">
                        <label class="checkbox-container">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="form-check-input">
                            <span class="checkbox-label">Se souvenir de moi</span>
                        </label>

                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Mot de passe oublié ?
                        </a>
                        @endif
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-primary btn-login">
                        <span class="btn-icon">→</span>
                        Se connecter
                        <span class="btn-loading"></span>
                    </button>

                    <!-- Lien d'inscription (si applicable) -->
                    @if(Route::has('register'))
                    <div class="register-link">
                        Nouveau sur DataCenterPro ?
                        <a href="{{ route('register') }}">Créer un compte</a>
                    </div>
                    @endif
                </form>

                <!-- Informations de sécurité -->
                <div class="security-info">
                    <div class="security-icon">🛡️</div>
                    <div class="security-text">
                        <strong>Sécurité maximale :</strong> Toutes les connexions sont chiffrées
                    </div>
                </div>
            </div>

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

    // Animation du bouton de connexion
    document.querySelector('.login-form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.btn-login');
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
</script>

@endsection