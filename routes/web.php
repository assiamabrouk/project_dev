<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{CategorieRessourceController, UtilisateurController, ProfileController, RessourceController, ReservationController, NotificationController, DashboardController, MaintenanceController, StatistiqueController, DiscussionController};
use App\Http\Controllers\Auth\{RegisteredUserController, AuthenticatedSessionController};

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::view('/regles', 'pages.rules')->name('rules');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('reservations', ReservationController::class);
    Route::get('/reservations/create/{ressource}', [ReservationController::class, 'create'])
     ->name('reservations.create');

    Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('/reservations/{id}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');

    Route::resource('notifications', NotificationController::class);
    Route::resource('utilisateurs', UtilisateurController::class);

    // Routes supplémentaires pour les ressources
    Route::post('/ressources/{id}/change-status', [RessourceController::class, 'changeStatus'])->name('ressources.change-status');
    Route::get('/ressources/{id}/stats', [RessourceController::class, 'stats'])->name('ressources.stats');
    Route::get('/ressources/search', [RessourceController::class, 'search'])->name('ressources.search');
    Route::get('/ressources/by-category/{categoryId}', [RessourceController::class, 'byCategory'])->name('ressources.by-category');
    Route::get('/ressources/available', [RessourceController::class, 'getAvailable'])->name('ressources.available');

    // Maintenance routes
    Route::resource('maintenances', MaintenanceController::class);

    // Statistiques
    Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index');

    // Discussions
    Route::get('/ressources/{ressourceId}/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/ressources/{ressourceId}/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::post('/discussions/{id}/moderate', [DiscussionController::class, 'moderate'])->name('discussions.moderate');
    Route::delete('/discussions/{id}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');

    Route::get('/users', [UtilisateurController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UtilisateurController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UtilisateurController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle-status', [UtilisateurController::class, 'toggleStatus'])->name('users.toggle-status');
});

Route::resource('categorie_ressources', CategorieRessourceController::class);
Route::resource('ressources', RessourceController::class);

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

require __DIR__ . '/auth.php';

Route::get('/categorie_ressources/stats',[CategorieRessourceController::class, 'stats'])->name('categorie_ressources.stats');

// routes/web.php
Route::resource('ressources', RessourceController::class);

Route::post('ressources/{ressource}/change-status', [RessourceController::class, 'changeStatus'])
    ->name('ressources.changeStatus');

// routes/web.php

// Routes pour les ressources
Route::resource('ressources', RessourceController::class);

// Route pour changer le statut d'une ressource
Route::post('ressources/{ressource}/change-status', [RessourceController::class, 'changeStatus'])
    ->name('ressources.changeStatus')
    ->middleware('auth');

// Routes pour les discussions
Route::get('ressources/{ressource}/discussions', [DiscussionController::class, 'index'])
    ->name('discussions.index')
    ->middleware('auth');

Route::post('ressources/{ressource}/discussions', [DiscussionController::class, 'store'])
    ->name('discussions.store')
    ->middleware('auth');

Route::post('discussions/{discussion}/moderate', [DiscussionController::class, 'moderate'])
    ->name('discussions.moderate')
    ->middleware('auth');

Route::delete('discussions/{discussion}', [DiscussionController::class, 'destroy'])
    ->name('discussions.delete')
    ->middleware('auth');

// Routes pour la gestion des rôles et responsabilités (Admin seulement)
Route::middleware(['auth'])->group(function () {
    
    // Prefix pour l'administration
    Route::prefix('admin')->middleware('can:admin')->group(function () {
        
        // ========== GESTION DES RÔLES ET RESPONSABILITÉS ==========
        
        // Formulaire de gestion des responsabilités
        Route::get('/users/{user}/gestion-responsabilites', 
            [UtilisateurController::class, 'showGestionResponsabilites'])
            ->name('users.gestion-responsabilites.form');
        
        // Traitement de la gestion des responsabilités
        Route::post('/users/{user}/gestion-responsabilites', 
            [UtilisateurController::class, 'gestionResponsabilites'])
            ->name('users.gestion-responsabilites');
        
        // API pour assigner/retirer une catégorie (AJAX)
        Route::post('/users/{user}/categorie/{categorie}/toggle-assignation', 
            [UtilisateurController::class, 'toggleAssignationCategorie'])
            ->name('users.categorie.toggle-assignation');
        
        // Retirer toutes les catégories d'un responsable
        Route::post('/users/{user}/retirer-toutes-categories', 
            [UtilisateurController::class, 'retirerToutesCategories'])
            ->name('users.retirer-toutes-categories');
        
        // Changer le rôle sans affecter les catégories
        Route::post('/users/{user}/changer-role-seul', 
            [UtilisateurController::class, 'changerRoleSeul'])
            ->name('users.changer-role-seul');
        
        // Transfert de responsabilité entre utilisateurs
        Route::get('/users/{user}/transfert-responsabilite', 
            [UtilisateurController::class, 'showTransfertResponsabilite'])
            ->name('users.transfert-responsabilite.form');
        
        Route::post('/users/{user}/transfert-responsabilite', 
            [UtilisateurController::class, 'transfertResponsabilite'])
            ->name('users.transfert-responsabilite');
    });
});