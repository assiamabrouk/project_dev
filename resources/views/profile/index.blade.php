@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="profile-container">
    <h1>Mon Profil</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="profile-info">
        <div class="profile-header">
            @if($user->img)
                <img src="{{ asset($user->img) }}" alt="Photo de profil" class="profile-image">
            @else
                <div class="profile-image-placeholder">
                    {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                </div>
            @endif
            
            <div class="profile-header-info">
                <h2>{{ $user->prenom }} {{ $user->nom }}</h2>
                <p class="profile-email">{{ $user->email }}</p>
                <div class="profile-badges">
                    <span class="badge badge-role">{{ ucfirst($user->role) }}</span>
                    <span class="badge badge-type">{{ ucfirst($user->user_type) }}</span>
                </div>
            </div>
        </div>
        
        <div class="profile-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $reservationsCount }}</span>
                <span class="stat-label">Réservations</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $notificationsCount }}</span>
                <span class="stat-label">Notifications non lues</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $user->created_at->format('d/m/Y') }}</span>
                <span class="stat-label">Membre depuis</span>
            </div>
        </div>
        
        <div class="profile-details">
            <div class="detail-row">
                <span class="detail-label">Téléphone:</span>
                <span class="detail-value">{{ $user->telephone ?? 'Non renseigné' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Statut:</span>
                <span class="detail-value badge badge-{{ $user->statut === 'actif' ? 'success' : 'danger' }}">
                    {{ ucfirst($user->statut) }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email vérifié:</span>
                <span class="detail-value">
                    @if($user->email_verified_at)
                        <span class="text-success">✓ Oui</span>
                    @else
                        <span class="text-danger">✗ Non</span>
                    @endif
                </span>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Modifier le profil
            </a>
            <a href="{{ route('profile.change-password') }}" class="btn btn-secondary">
                <i class="fas fa-key"></i> Changer le mot de passe
            </a>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

.profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #3498db;
}

.profile-image-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #3498db;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    border: 4px solid #2980b9;
}

.profile-header-info h2 {
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
}

.profile-email {
    color: #7f8c8d;
    margin: 0 0 1rem 0;
}

.profile-badges {
    display: flex;
    gap: 0.5rem;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-role {
    background: #3498db;
    color: white;
}

.badge-type {
    background: #2ecc71;
    color: white;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin: 2rem 0;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: white;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: bold;
    color: #3498db;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #7f8c8d;
    font-size: 0.875rem;
}

.profile-details {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin: 2rem 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 500;
    color: #2c3e50;
}

.detail-value {
    color: #34495e;
}

.profile-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    transition: all 0.3s;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .profile-actions {
        flex-direction: column;
    }
}
</style>
@endsection