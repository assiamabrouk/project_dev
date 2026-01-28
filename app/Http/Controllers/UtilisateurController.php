<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Gate;

class UtilisateurController extends Controller
{
    /**
     * Afficher la liste des utilisateurs.
     */
    public function index(Request $request)
    {
        // Vérifier les autorisations - méthode 1: using Gate facade
        if (!Gate::allows('viewAny', User::class)) {
            abort(403, 'Non autorisé');
        }
        
        // Méthode 2: using authorize() du Controller (requires UserPolicy)
        // $this->authorize('viewAny', User::class);

        // Construire la requête avec filtres
        $query = User::query();

        // Appliquer les filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('prenom', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('telephone', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Trier par date de création
        $query->orderBy('created_at', 'desc');

        // Paginer les résultats (20 par page)
        $users = $query->paginate(20)->withQueryString();

        // Statistiques
        $totalUsers = User::count();
        $activeUsers = User::where('statut', 'actif')->count();
        $responsablesCount = User::where('role', 'responsable')->count();
        $adminsCount = User::where('role', 'admin')->count();

        return view('utilisateurs.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'responsablesCount',
            'adminsCount'
        ));
    }

    /**
     * Afficher le formulaire de création d'un utilisateur.
     */
    public function create()
    {
        // Vérifier les autorisations
        if (!Gate::allows('create', User::class)) {
            abort(403, 'Non autorisé');
        }

        return view('users.create');
    }

    /**
     * Enregistrer un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        // Vérifier les autorisations
        if (!Gate::allows('create', User::class)) {
            abort(403, 'Non autorisé');
        }

        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'user_type' => 'required|in:ingenieur,enseignant,doctorant',
            'role' => 'required|in:admin,responsable,user',
            'statut' => 'required|in:actif,inactif',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('users', 'public');
            $validated['img'] = $path;
        }

        // Créer l'utilisateur
        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'user_type' => $validated['user_type'],
            'role' => $validated['role'],
            'statut' => $validated['statut'],
            'password' => Hash::make($validated['password']),
            'img' => $validated['img'] ?? null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur.
     */
    public function show(User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('view', $user)) {
            abort(403, 'Non autorisé');
        }

        // Charger les réservations de l'utilisateur
        $user->load([
            'reservations' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(10);
            }
        ]);

        return view('users.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition d'un utilisateur.
     */
    public function edit(User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('update', $user)) {
            abort(403, 'Non autorisé');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur.
     */
    public function update(Request $request, User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('update', $user)) {
            abort(403, 'Non autorisé');
        }

        // Validation conditionnelle selon le rôle
        $validationRules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'user_type' => 'required|in:ingenieur,enseignant,doctorant',
            'password' => 'nullable|confirmed|min:8',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Seuls les admins peuvent modifier le rôle et le statut
        if (auth()->user()->role === 'admin') {
            $validationRules['role'] = 'required|in:admin,responsable,user';
            $validationRules['statut'] = 'required|in:actif,inactif';
        }

        $validated = $request->validate($validationRules);

        // Gérer l'upload de l'image
        if ($request->hasFile('img')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }
            
            $path = $request->file('img')->store('users', 'public');
            $validated['img'] = $path;
        }

        // Mettre à jour le mot de passe si fourni
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Mettre à jour l'utilisateur
        $user->update($validated);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Changer le statut d'un utilisateur (actif/inactif).
     */
    public function toggleStatus(Request $request, User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('update', $user)) {
            abort(403, 'Non autorisé');
        }

        $newStatus = $user->statut == 'actif' ? 'inactif' : 'actif';
        $user->update(['statut' => $newStatus]);

        $message = $newStatus == 'actif'
            ? 'Utilisateur activé avec succès.'
            : 'Utilisateur désactivé avec succès.';

        return back()->with('success', $message);
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('delete', $user)) {
            abort(403, 'Non autorisé');
        }

        // Empêcher l'utilisateur de se supprimer lui-même
        if (auth()->id() === $user->id) {
            return back()->with('error', 
                'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Vérifier si l'utilisateur a des réservations
        if ($user->reservations()->count() > 0) {
            return back()->with('error', 
                'Impossible de supprimer cet utilisateur car il a des réservations associées.');
        }

        // Supprimer l'image si elle existe
        if ($user->img && Storage::disk('public')->exists($user->img)) {
            Storage::disk('public')->delete($user->img);
        }

        // Supprimer l'utilisateur
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Exporter la liste des utilisateurs en CSV.
     */
    public function export()
    {
        // Vérifier les autorisations
        if (!Gate::allows('viewAny', User::class)) {
            abort(403, 'Non autorisé');
        }

        $users = User::all();

        $fileName = 'utilisateurs_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // En-têtes
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénom',
                'Email',
                'Téléphone',
                'Type',
                'Rôle',
                'Statut',
                'Date Création'
            ]);

            // Données
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->nom,
                    $user->prenom,
                    $user->email,
                    $user->telephone ?? '',
                    $user->user_type,
                    $user->role,
                    $user->statut,
                    $user->created_at->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('update', $user)) {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    /**
     * Afficher le formulaire d'édition du profil utilisateur connecté.
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil de l'utilisateur connecté.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'user_type' => 'required|in:ingenieur,enseignant,doctorant',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }
            $path = $request->file('img')->store('users', 'public');
            $validated['img'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Changer le mot de passe de l'utilisateur connecté.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Mot de passe changé avec succès.');
    }
}