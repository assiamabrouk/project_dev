<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use App\Models\Reservation;
use App\Models\User;
use App\Models\CategorieRessource;
use App\Models\HistoriqueRessource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ===== 1. STATISTIQUES GLOBALES PAR RÔLE =====
        $stats = $this->getGlobalStats($user);

        // ===== 2. STATISTIQUES PERSONNELLES =====
        $personalStats = $this->getPersonalStats($user);

        // ===== 3. HISTORIQUE DE L'UTILISATEUR =====
        $userHistory = $this->getUserHistory($user);

        // ===== 4. DONNÉES UTILISATEURS (POUR ADMINISTRATEURS) =====
        $usersData = $this->getUsersData($user);

        // ===== 5. DONNÉES POUR GRAPHIQUES =====
        $chartData = $this->getChartData($user);

        // ===== 6. RÉSERVATIONS RÉCENTES =====
        $recentReservations = $this->getRecentReservations($user);

        // ===== 7. RESSOURCES PAR STATUT =====
        $ressourcesByStatus = $this->getRessourcesByStatus($user);

        return view('dashboard.index', array_merge(
            $stats,
            $personalStats,
            [
                'recentReservations' => $recentReservations,
                'chartData' => $chartData,
                'ressourcesByStatus' => $ressourcesByStatus,
                'userHistory' => $userHistory,
                'usersData' => $usersData,
                'currentUserRole' => $user->role
            ]
        ));
    }

    // ==================== MÉTHODES PRIVÉES ====================

    /**
     * Obtenir les statistiques globales selon le rôle
     */
    private function getGlobalStats($user)
    {
        switch ($user->role) {
            case 'admin':
                // Administrateur voit tout
                $totalRessources = Ressource::count();
                $totalUsers = User::count();
                $totalCategories = CategorieRessource::count();
                $activeReservations = Reservation::where('statut', 'approuvee')
                    ->where('date_fin', '>=', now())
                    ->where('date_debut', '<=', now())
                    ->count();
                $pendingReservations = Reservation::where('statut', 'en_attente')->count();
                $completedReservations = Reservation::where('statut', 'termine')->count();
                break;

            case 'responsable':
                // Responsable voit seulement sa catégorie
                $categories = $user->categorieRessources->pluck('id_categorie');

                $totalRessources = Ressource::whereIn('id_categorie', $categories)->count();
                $totalUsers = User::whereHas('reservations', function ($q) use ($categories) {
                    $q->whereHas('ressource', function ($q2) use ($categories) {
                        $q2->whereIn('id_categorie', $categories);
                    });
                })->count();
                $totalCategories = $categories->count();
                $activeReservations = Reservation::where('statut', 'approuvee')
                    ->where('date_fin', '>=', now())
                    ->where('date_debut', '<=', now())
                    ->whereHas('ressource', function ($q) use ($categories) {
                        $q->whereIn('id_categorie', $categories);
                    })
                    ->count();
                $pendingReservations = Reservation::where('statut', 'en_attente')
                    ->whereHas('ressource', function ($q) use ($categories) {
                        $q->whereIn('id_categorie', $categories);
                    })
                    ->count();
                $completedReservations = Reservation::where('statut', 'termine')
                    ->whereHas('ressource', function ($q) use ($categories) {
                        $q->whereIn('id_categorie', $categories);
                    })
                    ->count();
                break;

            default: // utilisateur normal
                $totalRessources = Ressource::where('statut', 'disponible')->count();
                $totalUsers = User::count(); // Peut-être limiter
                $totalCategories = CategorieRessource::count();
                $activeReservations = $user->reservations()
                    ->where('statut', 'approuvee')
                    ->where('date_fin', '>=', now())
                    ->where('date_debut', '<=', now())
                    ->count();
                $pendingReservations = $user->reservations()->where('statut', 'en_attente')->count();
                $completedReservations = $user->reservations()->where('statut', 'termine')->count();
                break;
        }

        // Taux d'occupation
        $occupationRate = $totalRessources > 0 ?
            round(($activeReservations / $totalRessources) * 100, 1) : 0;

        return [
            'totalRessources' => $totalRessources,
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories,
            'activeReservations' => $activeReservations,
            'pendingReservations' => $pendingReservations,
            'completedReservations' => $completedReservations,
            'occupationRate' => $occupationRate,
        ];
    }

    /**
     * Obtenir les statistiques personnelles
     */
    private function getPersonalStats($user)
    {
        $myReservationsCount = $user->reservations()->count();
        $myPendingReservations = $user->reservations()->where('statut', 'en_attente')->count();
        $myApprovedReservations = $user->reservations()->where('statut', 'approuvee')->count();
        $myActiveReservations = $user->reservations()
            ->where('statut', 'approuvee')
            ->where('date_fin', '>=', now())
            ->where('date_debut', '<=', now())
            ->count();

        return [
            'myReservationsCount' => $myReservationsCount,
            'myPendingReservations' => $myPendingReservations,
            'myApprovedReservations' => $myApprovedReservations,
            'myActiveReservations' => $myActiveReservations,
        ];
    }

    /**
     * Obtenir l'historique de l'utilisateur
     */
    private function getUserHistory($user)
    {
        // Historique des réservations de l'utilisateur
        $reservationHistory = $user->reservations()
            ->with('ressource')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Si l'utilisateur est admin ou responsable, on ajoute l'historique des ressources
        $resourceHistory = collect();
        if (in_array($user->role, ['admin', 'responsable'])) {
            $resourceHistory = HistoriqueRessource::with(['ressource', 'reservation'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        return [
            'reservationHistory' => $reservationHistory,
            'resourceHistory' => $resourceHistory,
        ];
    }

    /**
     * Obtenir les données utilisateurs pour le contrôle (admin seulement)
     */
    private function getUsersData($user)
    {
        if (!in_array($user->role, ['admin'])) {
            return [
                'allUsers' => collect(),
                'supervisors' => collect(),
                'activeUsers' => 0,
                'inactiveUsers' => 0,
            ];
        }

        // Tous les utilisateurs pour le contrôle admin
        $allUsers = User::orderBy('created_at', 'desc')->get();

        // Superviseurs (responsables)
        $supervisors = User::where('role', 'responsable')->get();

        // Statistiques utilisateurs 
        $activeUsers = User::where('statut', 'actif')->count();
        $inactiveUsers = User::where('statut', 'inactif')->count();

        return [
            'allUsers' => $allUsers,
            'supervisors' => $supervisors,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
        ];
    }

    /**
     * Obtenir les données pour les graphiques
     */
    private function getChartData($user)
    {
        $chartData = [];

        if ($user->role === 'admin') {
            // Toutes les réservations
            $reservationsPerMonth = Reservation::selectRaw('MONTH(date_debut) as month, COUNT(*) as count')
                ->whereYear('date_debut', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
        } elseif ($user->role === 'responsable') {
            // Réservations dans les catégories du responsable
            $categories = $user->categorieRessources->pluck('id_categorie');

            $reservationsPerMonth = Reservation::whereHas('ressource', function ($q) use ($categories) {
                $q->whereIn('id_categorie', $categories);
            })
                ->selectRaw('MONTH(date_debut) as month, COUNT(*) as count')
                ->whereYear('date_debut', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
        } else {
            // Réservations de l'utilisateur
            $reservationsPerMonth = $user->reservations()
                ->selectRaw('MONTH(date_debut) as month, COUNT(*) as count')
                ->whereYear('date_debut', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
        }

        // Remplir les mois manquants
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $reservationsPerMonth[$i] ?? 0;
        }

        return $chartData;
    }

    /**
     * Obtenir les réservations récentes
     */
    private function getRecentReservations($user)
    {
        if ($user->role === 'admin') {
            return Reservation::with(['utilisateur', 'ressource.categorie'])
                ->latest('created_at')
                ->take(5)
                ->get();
        } elseif ($user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie');

            return Reservation::whereHas('ressource', function ($q) use ($categories) {
                $q->whereIn('id_categorie', $categories);
            })
                ->with(['utilisateur', 'ressource.categorie'])
                ->latest('created_at')
                ->take(5)
                ->get();
        } else {
            return $user->reservations()
                ->with(['ressource.categorie'])
                ->latest('created_at')
                ->take(5)
                ->get();
        }
    }

    /**
     * Obtenir les ressources par statut
     */
    private function getRessourcesByStatus($user)
    {
        if ($user->role === 'admin') {
            return Ressource::select('statut', DB::raw('COUNT(*) as count'))
                ->groupBy('statut')
                ->get()
                ->pluck('count', 'statut')
                ->toArray();
        } elseif ($user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie');

            return Ressource::whereIn('id_categorie', $categories)
                ->select('statut', DB::raw('COUNT(*) as count'))
                ->groupBy('statut')
                ->get()
                ->pluck('count', 'statut')
                ->toArray();
        } else {
            return Ressource::where('statut', 'disponible')
                ->select('statut', DB::raw('COUNT(*) as count'))
                ->groupBy('statut')
                ->get()
                ->pluck('count', 'statut')
                ->toArray();
        }
    }
}