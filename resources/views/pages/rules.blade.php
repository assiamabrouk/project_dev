@extends('layouts.app')

@section('title', 'À propos - Data Center Manager')

@section('content')
<div class="about-container">
    <!-- Main Content -->
    <div class="main-content">
        <!-- Mission Section -->
        <section class="section mission-section">
            <div class="section-header">
                <h2><i class="fas fa-bullseye"></i> Notre Mission</h2>
                <div class="section-divider"></div>
            </div>
            <div class="mission-content">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>Optimiser</h3>
                    <p>Maximiser l'utilisation des ressources informatiques tout en réduisant les coûts opérationnels.</p>
                </div>
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Sécuriser</h3>
                    <p>Assurer la sécurité et la disponibilité des ressources critiques du data center.</p>
                </div>
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Superviser</h3>
                    <p>Surveiller en temps réel l'état et les performances de toutes les ressources.</p>
                </div>
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Simplifier</h3>
                    <p>Faciliter la gestion des ressources pour les administrateurs et les utilisateurs.</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="section features-section">
            <div class="section-header">
                <h2><i class="fas fa-star"></i> Fonctionnalités Principales</h2>
                <div class="section-divider"></div>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3>Gestion des Ressources</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Inventaire complet des serveurs</li>
                        <li><i class="fas fa-check"></i> Machines virtuelles</li>
                        <li><i class="fas fa-check"></i> Équipements réseau</li>
                        <li><i class="fas fa-check"></i> Baies de stockage</li>
                    </ul>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Réservation Intelligente</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Planning des ressources</li>
                        <li><i class="fas fa-check"></i> Gestion des conflits</li>
                        <li><i class="fas fa-check"></i> Approbation automatique</li>
                        <li><i class="fas fa-check"></i> Notifications en temps réel</li>
                    </ul>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Analytics & Rapports</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Tableaux de bord interactifs</li>
                        <li><i class="fas fa-check"></i> Statistiques d'utilisation</li>
                        <li><i class="fas fa-check"></i> Rapports personnalisés</li>
                        <li><i class="fas fa-check"></i> Prédiction des besoins</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Architecture Section -->
        <section class="section architecture-section">
            <div class="section-header">
                <h2><i class="fas fa-sitemap"></i> Architecture Technique</h2>
                <div class="section-divider"></div>
            </div>
            <div class="architecture-content">
                <div class="tech-stack">
                    <div class="tech-item">
                        <div class="tech-logo backend">
                            <i class="fab fa-laravel"></i>
                        </div>
                        <div class="tech-info">
                            <h4>Backend</h4>
                            <p>Laravel 10 - PHP 8.2</p>
                        </div>
                    </div>
                    <div class="tech-item">
                        <div class="tech-logo frontend">
                            <i class="fab fa-js-square"></i>
                        </div>
                        <div class="tech-info">
                            <h4>Frontend</h4>
                            <p>JavaScript ES6+ - Blade</p>
                        </div>
                    </div>
                    <div class="tech-item">
                        <div class="tech-logo database">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="tech-info">
                            <h4>Base de données</h4>
                            <p>MySQL 8.0</p>
                        </div>
                    </div>
                    <div class="tech-item">
                        <div class="tech-logo security">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="tech-info">
                            <h4>Sécurité</h4>
                            <p>Authentification multi-niveaux</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="section team-section">
            <div class="section-header">
                <h2><i class="fas fa-users"></i> Équipe de Développement</h2>
                <div class="section-divider"></div>
            </div>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Équipe Technique</h3>
                    <p>Développeurs Full-Stack spécialisés dans les solutions de gestion d'infrastructure.</p>
                    <div class="team-skills">
                        <span class="skill-tag">Laravel</span>
                        <span class="skill-tag">Vue.js</span>
                        <span class="skill-tag">Docker</span>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3>Équipe Infrastructure</h3>
                    <p>Experts en architecture système et gestion de data centers.</p>
                    <div class="team-skills">
                        <span class="skill-tag">VMware</span>
                        <span class="skill-tag">AWS</span>
                        <span class="skill-tag">Kubernetes</span>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Équipe Sécurité</h3>
                    <p>Spécialistes en sécurité informatique et protection des données.</p>
                    <div class="team-skills">
                        <span class="skill-tag">ISO 27001</span>
                        <span class="skill-tag">GDPR</span>
                        <span class="skill-tag">Pentest</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section cta-section">
            <div class="cta-content">
                <h2>Prêt à optimiser votre infrastructure ?</h2>
                <p>Découvrez comment Data Center Manager peut transformer la gestion de vos ressources informatiques.</p>
                <div class="cta-buttons">
                    @auth
                        <a href="{{ route('ressources.index') }}" class="btn-cta primary">
                            <i class="fas fa-play-circle"></i>
                            Commencer à utiliser
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-cta primary">
                            <i class="fas fa-user-plus"></i>
                            Demander un compte
                        </a>
                        <a href="{{ route('login') }}" class="btn-cta secondary">
                            <i class="fas fa-sign-in-alt"></i>
                            Se connecter
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </div>
</div>

<style>
.about-container {
    min-height: calc(100vh - 300px);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Hero Section */
.hero-section {
    padding: 4rem 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}

@media (max-width: 992px) {
    .hero-section {
        grid-template-columns: 1fr;
        text-align: center;
    }
}

.hero-content {
    padding-right: 2rem;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.hero-title i {
    font-size: 3.5rem;
    color: #00bcd4;
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.hero-image {
    position: relative;
    height: 300px;
}

.floating-shapes {
    position: relative;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    top: 0;
    left: 20%;
    animation-delay: 0s;
    color: #00bcd4;
}

.shape-2 {
    top: 30%;
    right: 10%;
    animation-delay: 1s;
    color: #4caf50;
}

.shape-3 {
    bottom: 20%;
    left: 10%;
    animation-delay: 2s;
    color: #ff9800;
}

.shape-4 {
    bottom: 0;
    right: 30%;
    animation-delay: 3s;
    color: #e91e63;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

/* Main Content */
.main-content {
    background: white;
    border-radius: 40px 40px 0 0;
    padding: 4rem 2rem;
    color: #333;
}

/* Sections */
.section {
    margin-bottom: 5rem;
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-header h2 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.section-header h2 i {
    color: #3498db;
}

.section-divider {
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #3498db, #2c3e50);
    margin: 0 auto;
    border-radius: 2px;
}

/* Mission Section */
.mission-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.mission-card {
    text-align: center;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 15px;
    transition: transform 0.3s ease;
    border: 1px solid #e9ecef;
}

.mission-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.mission-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #3498db, #2c3e50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.mission-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.mission-card p {
    color: #666;
    line-height: 1.6;
}

/* Features Section */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    margin-bottom: 1.5rem;
}

.feature-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.feature-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-card li {
    padding: 0.5rem 0;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.feature-card li i {
    color: #27ae60;
}

/* Architecture Section */
.tech-stack {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.tech-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.tech-logo {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.tech-logo.backend {
    background: #ff2d20;
}

.tech-logo.frontend {
    background: #f7df1e;
    color: #333;
}

.tech-logo.database {
    background: #00758f;
}

.tech-logo.security {
    background: #4caf50;
}

.tech-info {
    flex: 1;
}

.tech-info h4 {
    margin: 0 0 0.25rem 0;
    color: #2c3e50;
}

.tech-info p {
    margin: 0 0 0.5rem 0;
    color: #666;
    font-size: 0.9rem;
}

.tech-tag {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 4px;
    font-size: 0.8rem;
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
}

/* Team Section */
.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.team-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.team-avatar {
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
}

.team-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.team-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.team-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
}

.skill-tag {
    padding: 0.25rem 0.75rem;
    background: #f1f5f9;
    color: #2c3e50;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Stats Section */
.stats-section {
    background: linear-gradient(135deg, #2c3e50, #34495e);
    border-radius: 20px;
    padding: 4rem 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.stat-item {
    text-align: center;
    color: white;
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    color: #00bcd4;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* CTA Section */
.cta-section {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    color: white;
}

.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    max-width: 600px;
    color: white;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta {
    padding: 1rem 2rem;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-cta.primary {
    background: white;
    color: #667eea;
}

.btn-cta.secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-cta:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .section-header h2 {
        font-size: 1.8rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .cta-content h2 {
        font-size: 1.8rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-cta {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>

<script>
// Counter animation for stats
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;
    
    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-count');
            const count = +counter.innerText.replace(',', '');
            const increment = target / speed;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment).toLocaleString();
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        
        // Start counter when section is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCount();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(counter.closest('.stat-item'));
    });
    
    // Floating shapes animation
    const shapes = document.querySelectorAll('.shape');
    shapes.forEach((shape, index) => {
        shape.style.animationDelay = `${index * 1.5}s`;
    });
});
</script>
@endsection