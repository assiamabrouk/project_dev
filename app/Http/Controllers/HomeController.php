<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ressource;
use App\Models\Reservation;
use App\Models\CategorieRessource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Ajoutez cette ligne

class HomeController extends Controller
{
    public function index()
    {
        $categories = CategorieRessource::withCount('ressources')->get();
        $totalRessources = Ressource::count();
        
        return view('welcome', compact('categories', 'totalRessources'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $data = [];
        
        switch ($user->role) {
            case 'admin':
                $data['total_users'] = User::count();
                $data['total_ressources'] = Ressource::count();
                $data['total_categories'] = CategorieRessource::count();
                $data['reservations_en_attente'] = Reservation::where('statut', 'en_attente')->count();
                $data['reservations_approuvees'] = Reservation::where('statut', 'approuvee')->count();
                $data['ressources_disponibles'] = Ressource::where('statut', 'disponible')->count();
                break;
                
            case 'responsable':
                $categoriesIds = CategorieRessource::where('user_id', $user->id)->pluck('id_categorie');
                $data['managed_ressources'] = Ressource::whereIn('id_categorie', $categoriesIds)->count();
                $data['ressources_disponibles'] = Ressource::whereIn('id_categorie', $categoriesIds)
                    ->where('statut', 'disponible')->count();
                $data['demandes_en_attente'] = Reservation::whereIn('id_ressource', function($query) use ($categoriesIds) {
                    $query->select('id_ressource')
                          ->from('ressources')
                          ->whereIn('id_categorie', $categoriesIds);
                })->where('statut', 'en_attente')->count();
                $data['reservations_actives'] = Reservation::whereIn('id_ressource', function($query) use ($categoriesIds) {
                    $query->select('id_ressource')
                          ->from('ressources')
                          ->whereIn('id_categorie', $categoriesIds);
                })->where('statut', 'approuvee')->count();
                break;
                
            case 'user':
                $data['mes_reservations'] = Reservation::where('user_id', $user->id)->count();
                $data['reservations_en_attente'] = Reservation::where('user_id', $user->id)
                    ->where('statut', 'en_attente')->count();
                $data['reservations_approuvees'] = Reservation::where('user_id', $user->id)
                    ->where('statut', 'approuvee')->count();
                $data['reservations_refusees'] = Reservation::where('user_id', $user->id)
                    ->where('statut', 'refusee')->count();
                break;
        }
        
        $latestReservations = Reservation::where('user_id', $user->id)
            ->with('ressource')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('lu', false)
            ->get();
        
        return view('dashboard', compact('data', 'latestReservations', 'unreadNotifications'));
    }

    public function profile()
    {
        $user = Auth::user();
        $reservationsCount = Reservation::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('user_id', $user->id)
            ->where('lu', false)
            ->count();
        
        return view('profile', compact('user', 'reservationsCount', 'notificationsCount'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type' => 'required|in:ingenieur,enseignant,doctorant',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Gestion de l'image
        if ($request->hasFile('img')) {
            $imageName = time() . '.' . $request->img->extension();
            $request->img->move(public_path('images/profiles'), $imageName);
            $validated['img'] = 'images/profiles/' . $imageName;
            
            // Supprimer l'ancienne image si elle existe
            if ($user->img && file_exists(public_path($user->img))) {
                unlink(public_path($user->img));
            }
        }
        
        // Mise à jour de l'utilisateur
        User::where('id', $user->id)->update($validated);
        
        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect']);
        }
        
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('success', 'Mot de passe changé avec succès');
    }
}