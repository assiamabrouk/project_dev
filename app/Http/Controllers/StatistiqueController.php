<?php
// app/Http\Controllers/StatistiqueController.php
namespace App\Http\Controllers;

use App\Models\Ressource;
use App\Models\Reservation;
use App\Models\User;
use App\Models\CategorieRessource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminStats();
        } elseif ($user->role === 'responsable') {
            return $this->responsableStats($user);
        } else {
            return $this->userStats($user);
        }
    }

    private function adminStats()
    {
        // Statistiques globales
        $totalRessources = Ressource::count();
        $totalUsers = User::count();
        $totalCategories = CategorieRessource::count();
        
        // Taux d'occupation
        $activeReservations = Reservation::where('statut', 'approuvee')
            ->where('date_fin', '>=', now())
            ->where('date_debut', '<=', now())
            ->count();
            
        $occupationRate = $totalRessources > 0 ? round(($activeReservations / $totalRessources) * 100, 2) : 0;

        // Réservations par statut
        $reservationsByStatus = Reservation::select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();

        // Ressources par catégorie
        $ressourcesByCategory = Ressource::select('categorie_ressources.nom', DB::raw('COUNT(*) as count'))
            ->join('categorie_ressources', 'ressources.id_categorie', '=', 'categorie_ressources.id_categorie')
            ->groupBy('categorie_ressources.nom')
            ->pluck('count', 'categorie_ressources.nom')
            ->toArray();

        // Utilisateurs par type
        $usersByType = User::select('user_type', DB::raw('COUNT(*) as count'))
            ->groupBy('user_type')
            ->pluck('count', 'user_type')
            ->toArray();

        // Évolution des réservations sur 12 mois
        $reservationsPerMonth = Reservation::select(
                DB::raw('YEAR(date_debut) as year'),
                DB::raw('MONTH(date_debut) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date_debut', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('statistiques.admin', compact(
            'totalRessources',
            'totalUsers',
            'totalCategories',
            'occupationRate',
            'reservationsByStatus',
            'ressourcesByCategory',
            'usersByType',
            'reservationsPerMonth'
        ));
    }

    private function responsableStats($user)
    {
        $categoriesIds = $user->categorieRessources->pluck('id_categorie');
        
        // Ressources gérées
        $managedRessources = Ressource::whereIn('id_categorie', $categoriesIds)->count();
        
        // Occupation des ressources gérées
        $activeReservations = Reservation::whereIn('id_ressource', function($query) use ($categoriesIds) {
                $query->select('id_ressource')
                      ->from('ressources')
                      ->whereIn('id_categorie', $categoriesIds);
            })
            ->where('statut', 'approuvee')
            ->where('date_fin', '>=', now())
            ->where('date_debut', '<=', now())
            ->count();
            
        $occupationRate = $managedRessources > 0 ? round(($activeReservations / $managedRessources) * 100, 2) : 0;

        // Demandes par statut
        $demandesByStatus = Reservation::whereIn('id_ressource', function($query) use ($categoriesIds) {
                $query->select('id_ressource')
                      ->from('ressources')
                      ->whereIn('id_categorie', $categoriesIds);
            })
            ->select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();

        return view('statistiques.responsable', compact(
            'managedRessources',
            'occupationRate',
            'demandesByStatus'
        ));
    }

    private function userStats($user)
    {
        $myReservations = Reservation::where('user_id', $user->id)
            ->select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();

        $reservationsPerMonth = Reservation::where('user_id', $user->id)
            ->select(
                DB::raw('YEAR(date_debut) as year'),
                DB::raw('MONTH(date_debut) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('date_debut', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('statistiques.user', compact('myReservations', 'reservationsPerMonth'));
    }
}