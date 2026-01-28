<?php
// app/Http\Controllers/ProfileController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur.
     */
    public function index()
    {
        $user = Auth::user();
        $reservationsCount = Reservation::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('user_id', $user->id)
            ->where('lu', false)
            ->count();
        
        return view('profile.index', compact('user', 'reservationsCount', 'notificationsCount'));
    }

    /**
     * Afficher le formulaire d'édition du profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil de l'utilisateur.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type' => 'required|in:ingenieur,enseignant,doctorant',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        // Gestion de l'image
        if ($request->hasFile('img')) {
            // Créer le dossier si nécessaire
            if (!file_exists(public_path('images/profiles'))) {
                mkdir(public_path('images/profiles'), 0777, true);
            }
            
            // Générer un nom unique
            $imageName = 'profile_' . $user->id . '_' . time() . '.' . $request->img->extension();
            
            // Déplacer l'image
            $request->img->move(public_path('images/profiles'), $imageName);
            $validated['img'] = 'images/profiles/' . $imageName;
            
            // Supprimer l'ancienne image si elle existe
            if ($user->img && file_exists(public_path($user->img))) {
                unlink(public_path($user->img));
            }
        }
        
        // Mise à jour - méthode correcte
        // $user->update($validated);
        
        return redirect()->route('profile.index')->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Afficher le formulaire de changement de mot de passe.
     */
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    /**
     * Changer le mot de passe de l'utilisateur.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }
        
        return redirect()->route('profile.index')->with('success', 'Mot de passe changé avec succès');
    }

    /**
     * Supprimer le compte de l'utilisateur.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        
        $user = Auth::user();
        
        // Récupérer l'ID de l'utilisateur avant la déconnexion
        $userId = $user->id;
        
        // Supprimer l'image de profil si elle existe
        if ($user->img && file_exists(public_path($user->img))) {
            unlink(public_path($user->img));
        }
        
        // Déconnecter l'utilisateur
        Auth::logout();
        
        // Supprimer l'utilisateur en utilisant le modèle User
        User::destroy($userId);
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès');
    }
}