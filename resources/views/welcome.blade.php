{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'Bienvenue - DataCenterPro')

@section('content')
{{-- Page d'accueil professionnelle avec héros section --}}
<div class="welcome-page">
    {{-- Section héros avec fond dégradé --}}
    <section class="hero-section">
        <div class="hero-background">
            <div class="gradient-circle circle-1"></div>
            <div class="gradient-circle circle-2"></div>
            <div class="gradient-circle circle-3"></div>
        </div>

        <div class="hero-container">
            <div class="hero-content animate-fade-in">
                <img src="{{ asset('img/logo.png') }}" alt="DataCenterPro Logo" class="hero-logo">
                <h1 class="hero-title"> DataCenterPro </h1>
                <p class="hero-subtitle">
                    Gestion de vos ressources et réservations facilement.<br>
                    Une solution complète pour optimiser votre infrastructure.
                </p>

                {{-- Actions principales --}}
                <div class="hero-actions">
                    @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg"> Accéder au Tableau de Bord</a>
                    <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg"> Nouvelle Réservation</a>
                    @else
                    <div class="login">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg"> Se Connecter </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg"> S'inscrire </a>
                    </div>
                    @endauth
                </div>
            </div>

            {{-- Illustration SVG --}}
            <div class="hero-illustration animate-slide-in">
                <svg viewBox="0 0 500 300">
                    <!-- Serveurs -->
                    <g class="servers">
                        <rect x="50" y="50" width="120" height="280" rx="8" class="server-body" />
                        <rect x="60" y="60" width="100" height="30" rx="4" class="server-led" />
                        <rect x="60" y="100" width="100" height="15" rx="2" class="server-detail" />
                        <rect x="60" y="125" width="100" height="15" rx="2" class="server-detail" />
                        <rect x="60" y="145" width="100" height="15" rx="2" class="server-detail" />
                        <rect x="60" y="165" width="100" height="15" rx="2" class="server-detail" />
                    </g>

                    <!-- Stockage -->
                    <g class="storage">
                        <rect x="200" y="100" width="150" height="120" rx="8" class="storage-body" />
                        <circle cx="230" cy="130" r="15" class="storage-disk" />
                        <circle cx="280" cy="130" r="15" class="storage-disk" />
                        <circle cx="330" cy="130" r="15" class="storage-disk" />
                    </g>

                    <!-- Réseau -->
                    <g class="network">
                        <rect x="380" y="70" width="80" height="80" rx="8" class="network-device" />
                        <circle cx="420" cy="110" r="25" class="network-core" />
                        <line x1="420" y1="85" x2="420" y2="135" class="network-line" />
                        <line x1="395" y1="110" x2="445" y2="110" class="network-line" />
                    </g>

                    <!-- Connecteurs -->
                    <line x1="170" y1="160" x2="200" y2="160" class="connection-line" />
                    <line x1="350" y1="160" x2="380" y2="160" class="connection-line" />
                    <line x1="110" y1="230" x2="110" y2="260" class="connection-line" />
                    <line x1="275" y1="220" x2="275" y2="260" class="connection-line" />
                </svg>
            </div>
        </div>
    </section>

    {{-- Section navigation rapide --}}
    <section class="quick-nav-section">
        <div class="section-header">
            <h2 class="page-title text-gradient">Accès Rapide</h2>
            <p class="page-subtitle">Naviguez rapidement vers les principales fonctionnalités</p>
        </div>

        <div class="quick-nav-grid">
            {{-- Catégorie de ressources --}}
            <a href="{{ route('categorie_ressources.index') }}" class="quick-nav-card animate-card">
                <div class="nav-card-icon"> <i class="fas fa-layer-group"></i></div>
                <h3 class="nav-card-title">Catégories</h3>
                <p class="nav-card-description">Gérez les catégories de ressources informatiques</p>
                <span class="nav-card-arrow">→</span>
            </a>

            {{-- Ressources --}}
            <a href="{{ route('ressources.index') }}" class="quick-nav-card animate-card">
                <div class="nav-card-icon"><i class="fas fa-server"></i></div>
                <h3 class="nav-card-title">Ressources</h3>
                <p class="nav-card-description">Serveurs, stockage et équipements réseau</p>
                <span class="nav-card-arrow">→</span>
            </a>

            {{-- Réservations --}}
            <a href="{{ route('reservations.index') }}" class="quick-nav-card animate-card">
                <div class="nav-card-icon"><i class="fas fa-calendar-check"></i></div>
                <h3 class="nav-card-title">Réservations</h3>
                <p class="nav-card-description">Gérez vos réservations et demandes</p>
                <span class="nav-card-arrow">→</span>
            </a>

            @auth
            {{-- Notifications --}}
            <a href="{{ route('notifications.index') }}" class="quick-nav-card animate-card">
                <div class="nav-card-icon"> <i class="fas fa-bell"></i></div>
                <h3 class="nav-card-title">Notifications</h3>
                <p class="nav-card-description">Restez informé de vos activités</p>

                @php
                $unreadCount = auth()->check() ? auth()->user()->notifications()->where('lu', false)->count(): 0;
                @endphp

                @if($unreadCount > 0)
                <span class="nav-card-badge">{{ $unreadCount }}</span>
                @endif

                <span class="nav-card-arrow">→</span>
            </a>

            {{-- Statistiques --}}
            <a href="{{ route('dashboard') }}" class="quick-nav-card animate-card">
                <div class="nav-card-icon"><i class="fas fa-chart-line"></i></div>
                <h3 class="nav-card-title">Tableau de Bord</h3>
                <p class="nav-card-description">Vue d'ensemble et statistiques</p>
                <span class="nav-card-arrow">→</span>
            </a>

            {{-- Utilisateurs (visible uniquement pour les admins) --}}
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('utilisateurs.index') }}" class="quick-nav-card animate-card">
                <div class="nav-card-icon"><i class="fas fa-users"></i></div>
                <h3 class="nav-card-title">Utilisateurs</h3>
                <p class="nav-card-description">Gestion des comptes et permissions</p>
                <span class="nav-card-arrow">→</span>
            </a>
            @endif

            @endauth
        </div>
    </section>

    {{-- Section fonctionnalités --}}
    @guest
    <section class="features-section">
        <div class="section-header">
            <h2 class="section-title">Pourquoi choisir DataCenterPro ?</h2>
            <p class="section-subtitle">Une solution complète pour la gestion de votre Data Center</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"> <i class="fas fa-shield-alt"></i></div>
                <h3>Sécurisé</h3>
                <p>Protection avancée de vos données avec authentification multi-facteurs et chiffrement</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"> <i class="fas fa-bolt"></i></div>
                <h3>Rapide</h3>
                <p>Interface optimisée pour une gestion efficace et des temps de réponse réduits</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-user-shield"></i></div>
                <h3>Multi-rôles</h3>
                <p>Gestion différenciée des accès selon les profils utilisateurs</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Analytique</h3>
                <p>Tableaux de bord détaillés et statistiques en temps réel</p>
            </div>
        </div>
    </section>
    @endguest
</div>

<style>
    /* Variables supplémentaires */
    :root {
        --hero-gradient: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, #7c3aed 100%);
        --shadow-soft: 0 8px 32px rgba(31, 38, 135, 0.1);
    }

    /* Conteneur principal */
    .welcome-page {
        min-height: 100vh;
    }

    /* Section héros */
    .hero-section {
        position: relative;
        padding: 0 0 25px 0;
        margin: 0 0 40px 0;
        display: flex;
        align-items: center;
        overflow: hidden;
        background: var(--hero-gradient);
    }

    /*Logo principal (Hero section)*/
    .hero-logo {
        width: 300px;
        margin: auto;
        animation: logo-float 3s ease-in-out infinite;
        user-select: none;
    }

    /* Animation flottante légère */
    @keyframes logo-float {
        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0);
        }
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        overflow: hidden;
        padding: 0;
        margin: 0;
    }

    .gradient-circle {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
    }

    .circle-1 {
        width: 600px;
        height: 600px;
        top: -300px;
        right: -200px;
    }

    .circle-2 {
        width: 400px;
        height: 400px;
        bottom: -200px;
        left: -100px;
    }

    .circle-3 {
        width: 300px;
        height: 300px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .hero-container {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-2xl);
        align-items: center;
    }

    .hero-content {
        max-width: 600px;
    }

    .hero-title {
        font-size: var(--font-size-4xl);
        font-weight: 800;
        text-align: center;
        line-height: 1.1;
        color: white;
    }

    .hero-subtitle {
        color: black;
        text-align: center;
        font-size: 20px;
        line-height: 1.6;
    }

    .hero-actions {
        display: flex;
        gap: var(--space-md);
        flex-wrap: wrap;
    }

    /* Illustration Data Center */
    .hero-illustration {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .server-body {
        fill: #ffffff;
        stroke: #cbd5e1;
        stroke-width: 2;
    }

    .server-led {
        fill: var(--success);
        animation: pulse-led 2s infinite;
    }

    .server-detail {
        fill: #e2e8f0;
    }

    .storage-body {
        fill: #f8fafc;
        stroke: #cbd5e1;
        stroke-width: 2;
    }

    .storage-disk {
        fill: var(--primary-super-light);
        stroke: var(--primary);
        stroke-width: 2;

    }

    .network-device {
        fill: #f1f5f9;
        stroke: #cbd5e1;
        stroke-width: 2;
    }

    .network-core {
        fill: var(--primary-super-light);
        stroke: var(--primary);
        stroke-width: 3;
    }

    .network-line {
        stroke: var(--primary);
        stroke-width: 2;
    }

    .connection-line {
        stroke: white;
        stroke-width: 3;
        stroke-dasharray: 7, 9;
        animation: dash 20s linear infinite;
    }

    .login {
        width: fit-content;
        margin: 0 auto;
    }

    /* Section navigation rapide */
    .quick-nav-section {
        max-width: 1200px;
        margin: 0 auto var(--space-2xl);
        padding: 0 var(--space-lg);
    }

    .section-header {
        text-align: center;
        margin-bottom: var(--space-2xl);
    }

    .section-title {
        font-size: var(--font-size-3xl);
        margin-bottom: var(--space-md);
        color: var(--text-main);
    }

    .section-subtitle {
        font-size: var(--font-size-lg);
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
    }

    .quick-nav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-lg);
    }

    .quick-nav-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: var(--space-xl);
        display: block;
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        color: var(--text-main);
    }

    .quick-nav-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary);
    }

    .quick-nav-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform var(--transition-normal);
    }

    .quick-nav-card:hover::before {
        transform: scaleX(1);
    }

    .nav-card-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-super-light);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: var(--space-lg);
        color: var(--primary);
    }

    .nav-card-title {
        font-size: var(--font-size-xl);
        font-weight: 600;
        margin-bottom: var(--space-sm);
    }

    .nav-card-description {
        color: var(--text-secondary);
        margin-bottom: var(--space-lg);
        line-height: 1.5;
    }

    .nav-card-arrow {
        position: absolute;
        right: var(--space-xl);
        bottom: var(--space-xl);
        font-size: 30px;
        color: var(--primary);
        transition: transform var(--transition-normal);
    }

    .quick-nav-card:hover .nav-card-arrow {
        transform: translateX(8px);
    }

    .nav-card-badge {
        position: absolute;
        top: var(--space-lg);
        right: var(--space-lg);
        background: var(--danger);
        color: var(--text-on-primary);
        width: 24px;
        height: 24px;
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--font-size-xs);
        font-weight: 600;
        animation: pulse 2s infinite;
    }

    /* Section fonctionnalités */
    .features-section {
        max-width: 1200px;
        margin: 0 auto var(--space-2xl);
        padding: 0 var(--space-lg);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-xl);
        margin-top: var(--space-2xl);
    }

    .feature-card {
        text-align: center;
        padding: var(--space-xl);
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        transition: all var(--transition-normal);
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        background: var(--primary-super-light);
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-lg);
        color: var(--primary);
    }

    .feature-card h3 {
        font-size: var(--font-size-xl);
        margin-bottom: var(--space-md);
        color: var(--text-main);
    }

    .feature-card p {
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* Animations */
    @keyframes pulse-led {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.2;
        }
    }

    @keyframes dash {
        to {
            stroke-dashoffset: 1000;
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out;
    }

    .animate-slide-in {
        animation: slideIn 1s ease-out;
    }

    .animate-card {
        animation: cardAppear 0.6s ease-out forwards;
        opacity: 0;
    }

    .animate-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .animate-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .animate-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .animate-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .animate-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .animate-card:nth-child(6) {
        animation-delay: 0.6s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(50px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes cardAppear {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

{{-- Scripts pour les animations --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Effet de parallaxe sur le héros
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-section');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
        });
    });
</script>
@endsection