<nav class="navbar">
    <div class="nav-container">

        <!-- Logo -->
        <div class="nav-logo">
            <a href="{{ route('home') }}" class="logo-link">
                <div class="logo-icon">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo DataCenterPro">
                </div>
                <span class="logo-text">DataCenter<span class="logo-highlight">Pro</span></span>
            </a>
        </div>

        <!-- Navigation Desktop -->
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('categorie_ressources.index') }}"
                    class="nav-link @if(request()->routeIs('categorie_ressources.*')) active @endif">
                    <i class="fas fa-database nav-icon"></i>
                    <span>Catégories</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('ressources.index') }}" class="nav-link @if(request()->routeIs('ressources.*')) active @endif">
                    <i class="fas fa-server" style="margin-right: 0.5rem;"></i> Ressources
                </a>
            </li>

            @guest
                <li class="nav-item">
                    <a href="{{ route('rules') }}" class="nav-link @if(request()->routeIs('rules')) active @endif">
                        <i class="fas fa-info-circle nav-icon"></i>
                        <span>Règles</span>
                    </a>
                </li>
            @endguest

            @auth
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                        <i class="fas fa-house nav-icon"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('reservations.index') }}"
                        class="nav-link @if(request()->routeIs('reservations.*')) active @endif">
                        <i class="fas fa-calendar-check nav-icon"></i>
                        <span>Réserv</span>
                        @php
                            $pendingCount = auth()->user()->reservations()->where('statut', 'en_attente')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="nav-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('utilisateurs.index') }}"
                            class="nav-link @if(request()->routeIs('utilisateurs.*')) active @endif">
                            <i class="fas fa-users nav-icon"></i>
                            <span>User</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('notifications.index') }}"
                        class="nav-link @if(request()->routeIs('notifications.*')) active @endif">
                        @php
                            $unreadCount = auth()->user()->notifications()->where('lu', false)->count();
                        @endphp
                        <span class="nav-badge notification-badge"><i class="fas fa-bell nav-icon"></i>
                            {{ $unreadCount }}</span>
                    </a>
                </li>
            @endauth
        </ul>

        <!-- User Menu -->
        <div class="user-menu-container">
            @auth
                <div class="user-profile" id="userProfile">
                    <img class="user-avatar"
                        src="{{ auth()->user()->img ? asset('storage/user/' . auth()->user()->img) : asset('img/default-user.png') }}"
                        alt="user">
                    <div class="user-info">
                        <span class="user-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
                        <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                    <i class="fas fa-chevron-down user-arrow"></i>
                </div>

                <!-- Dropdown -->
                <div class="dropdown-menu" id="userDropdown">

                    <div class="dropdown-header">
                        <img class="user-avatar"
                            src="{{ auth()->user()->img ? asset('storage/user/' . auth()->user()->img) : asset('img/default-user.png') }}"
                            alt="user">
                        <div>
                            <div class="dropdown-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                            <div class="dropdown-email">{{ auth()->user()->email }}</div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        Mon profil
                    </a>

                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-gear"></i>
                        Modifier les paramètres
                    </a>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item logout-item">
                            <i class="fas fa-right-from-bracket"></i>
                            Déconnexion
                        </button>
                    </form>

                </div>
            @else
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">S'inscrire</a>
                </div>
            @endauth
        </div>

        <!-- Mobile Toggle -->
        <button class="mobile-toggle" id="mobileToggle">
            <span class="toggle-line"></span>
            <span class="toggle-line"></span>
            <span class="toggle-line"></span>
        </button>

    </div>
</nav>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle mobile menu
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const body = document.body;

        mobileToggle.addEventListener('click', function () {
            mobileMenu.classList.toggle('active');
            mobileToggle.classList.toggle('active');
            body.classList.toggle('no-scroll');
        });

        // Close mobile menu when clicking on link
        const mobileLinks = document.querySelectorAll('.mobile-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                mobileToggle.classList.remove('active');
                body.classList.remove('no-scroll');
            });
        });

        // User dropdown
        const userProfile = document.getElementById('userProfile');
        const userDropdown = document.getElementById('userDropdown');

        if (userProfile && userDropdown) {
            userProfile.addEventListener('click', function (e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!userProfile.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });

            // Close dropdown when clicking on item
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('click', () => {
                    userDropdown.classList.remove('show');
                });
            });
        }

        // Add smooth animations
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-2px)';
            });

            item.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });

        // Notification badge animation
        const notificationBadges = document.querySelectorAll('.notification-badge');
        notificationBadges.forEach(badge => {
            badge.addEventListener('click', function () {
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = 'pulse 3s infinite';
                }, 10);
            });
        });
    });

    // Close dropdowns on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const dropdowns = document.querySelectorAll('.dropdown-menu.show');
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });

            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
                document.getElementById('mobileToggle').classList.remove('active');
                document.body.classList.remove('no-scroll');
            }
        }
    });
</script>

<style>
    /* Base de la navbar */
    .navbar {
        position: sticky;
        top: 0;
        z-index: var(--z-sticky);
        background-color: var(--surface);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border);
        box-shadow: var(--shadow-md);
        transition: all var(--transition-normal);
    }

    .nav-icon,
    .dropdown-item i,
    .logo-icon i {
        margin-right: 8px;
        font-size: 1.1rem;
    }


    .nav-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 var(--space-lg);
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Logo */
    .nav-logo {
        flex-shrink: 0;
    }

    .logo-link {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
        transition: transform var(--transition-normal);
    }

    .logo-link:hover {
        transform: scale(1.02);
    }

    .logo-icon {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-on-primary);
        padding: var(--space-sm);
    }

    .logo-icon svg {
        width: 24px;
        height: 24px;
    }

    .logo-text {
        font-size: var(--font-size-xl);
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .logo-highlight {
        color: var(--primary);
        font-weight: 800;
    }

    /* Navigation principale */
    .nav-menu {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: var(--space-xs);
        margin-left: var(--space-xl);
    }

    .nav-item {
        position: relative;
        transition: transform var(--transition-normal);
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        border-radius: var(--radius-md);
        color: var(--text-secondary);
        text-decoration: none;
        font-weight: 500;
        transition: all var(--transition-normal);
        position: relative;
        white-space: nowrap;
    }

    .nav-link:hover {
        background-color: var(--surface-hover);
        color: var(--primary);
        transform: translateY(-1px);
    }

    .nav-link.active {
        background-color: var(--primary-super-light);
        color: var(--primary);
        font-weight: 600;
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        border-radius: var(--radius-full);
    }

    .nav-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .nav-badge {
        background-color: var(--danger);
        color: var(--text-on-primary);
        font-size: var(--font-size-xs);
        font-weight: 600;
        min-width: 20px;
        height: 20px;
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px var(--space-xs);
        margin-left: var(--space-xs);
        animation: pulse 2s infinite;
    }

    .notification-badge {
        background-color: var(--primary);
        padding: 10px;
        font-size: 20px;
    }

    /* User menu */
    .user-menu-container {
        position: relative;
        margin-left: auto;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-xs) var(--space-md);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-normal);
        background-color: var(--surface);
        border: 1px solid transparent;
    }

    .user-profile:hover {
        background-color: var(--surface-hover);
        border-color: var(--border);
    }

    user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 12px;
        border-radius: 12px;
        cursor: pointer;
        background-color: var(--surface);
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .user-profile:hover {
        background-color: var(--surface-hover);
        border-color: var(--border);
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-super-light);
        transition: transform 0.3s ease;
    }

    .user-profile:hover .user-avatar {
        transform: scale(1.05);
    }

    .user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .user-role {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: capitalize;
    }

    .user-arrow {
        font-size: 0.8rem;
        color: var(--text-muted);
        transition: transform 0.3s ease;
    }

    .user-profile:hover .user-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown menu */
    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 280px;
        background-color: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all var(--transition-normal);
        z-index: var(--z-dropdown);
        overflow: hidden;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        background-color: var(--surface-hover);
        border-bottom: 1px solid var(--border);
    }

    .dropdown-avatar {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-full);
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: var(--text-on-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: var(--font-size-lg);
    }

    .dropdown-name {
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 2px;
    }

    .dropdown-email {
        font-size: var(--font-size-sm);
        color: var(--text-muted);
    }

    .dropdown-divider {
        height: 1px;
        background-color: var(--border);
        margin: var(--space-xs) 0;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-md) var(--space-lg);
        color: var(--text-secondary);
        text-decoration: none;
        transition: all var(--transition-fast);
        cursor: pointer;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        font-size: var(--font-size-base);
    }

    .dropdown-item:hover {
        background-color: var(--surface-hover);
        color: var(--primary);
    }

    .dropdown-item svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .admin-item {
        color: var(--primary);
        font-weight: 500;
    }

    .logout-item {
        color: var(--danger);
    }

    .dropdown-form {
        width: 100%;
    }

    /* Auth buttons for guests */
    .auth-buttons {
        display: flex;
        gap: var(--space-sm);
        align-items: center;
    }

    /* Mobile toggle */
    .mobile-toggle {
        display: none;
        flex-direction: column;
        justify-content: space-between;
        width: 30px;
        height: 24px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        z-index: var(--z-sticky);
    }

    .toggle-line {
        width: 100%;
        height: 2px;
        background-color: var(--text-main);
        border-radius: var(--radius-full);
        transition: all var(--transition-normal);
    }

    .mobile-toggle.active .toggle-line:nth-child(1) {
        transform: rotate(45deg) translate(6px, 6px);
    }

    .mobile-toggle.active .toggle-line:nth-child(2) {
        opacity: 0;
    }

    .mobile-toggle.active .toggle-line:nth-child(3) {
        transform: rotate(-45deg) translate(6px, -6px);
    }

    /* Mobile menu */
    .mobile-menu {
        position: fixed;
        top: 70px;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--surface);
        transform: translateX(100%);
        transition: transform var(--transition-normal);
        z-index: var(--z-modal);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .mobile-menu.active {
        transform: translateX(0);
    }

    .mobile-header {
        padding: var(--space-lg);
        background-color: var(--surface-hover);
        border-bottom: 1px solid var(--border);
    }

    .mobile-user {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }

    .mobile-avatar {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-full);
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: var(--text-on-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: var(--font-size-lg);
    }

    .mobile-name {
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 2px;
    }

    .mobile-role {
        font-size: var(--font-size-sm);
        color: var(--text-muted);
        text-transform: capitalize;
    }

    .mobile-auth {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }

    .mobile-nav {
        list-style: none;
        padding: 0;
        margin: 0;
        flex: 1;
    }

    .mobile-item {
        border-bottom: 1px solid var(--border-light);
    }

    .mobile-link {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-lg);
        color: var(--text-main);
        text-decoration: none;
        transition: all var(--transition-fast);
        position: relative;
    }

    .mobile-link:hover,
    .mobile-link.active {
        background-color: var(--surface-hover);
        color: var(--primary);
    }

    .mobile-link svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .mobile-badge {
        margin-left: auto;
        background-color: var(--danger);
        color: var(--text-on-primary);
        font-size: var(--font-size-xs);
        font-weight: 600;
        min-width: 22px;
        height: 22px;
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 var(--space-xs);
    }

    .mobile-footer {
        padding: var(--space-lg);
        border-top: 1px solid var(--border);
        background-color: var(--surface-hover);
    }

    .mobile-logout-form {
        width: 100%;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-sm);
    }

    /* Animation for badge */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Body no scroll when menu is open */
    body.no-scroll {
        overflow: hidden;
    }

    /* Hover effects */
    .nav-item:hover .nav-link {
        transform: translateY(-1px);
    }

    .nav-item:hover .nav-link::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background-color: var(--primary);
        border-radius: 50%;
        animation: dotPulse 1s infinite;
    }

    @keyframes dotPulse {

        0%,
        100% {
            opacity: 0.5;
            transform: translateX(-50%) scale(1);
        }

        50% {
            opacity: 1;
            transform: translateX(-50%) scale(1.2);
        }
    }
</style>