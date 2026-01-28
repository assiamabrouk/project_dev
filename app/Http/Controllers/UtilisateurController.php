<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Gate;
use App\Models\CategorieRessource;
use App\Models\Ressource;
use Illuminate\Support\Facades\DB;


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

        return view('utilisateurs.edit', compact('user'));
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

        
    /**
     * Afficher le formulaire de gestion des responsabilités d'un utilisateur
     * 
     * Permet de:
     * 1. Changer le rôle de l'utilisateur
     * 2. Assigner/retirer des catégories
     * 3. Voir les statistiques de responsabilité
     */
    public function showGestionResponsabilites(User $user)
    {
        // Vérifier que seul l'admin peut accéder
        if (!Gate::allows('admin')) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les responsabilités.');
        }

        // Vérifier que l'utilisateur n'est pas l'admin principal
        if ($user->email === 'admin@example.com') {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas modifier les responsabilités de l\'administrateur principal.');
        }

        // Récupérer toutes les catégories avec leurs informations
        $categories = CategorieRessource::with(['responsable', 'ressources'])
            ->orderBy('nom')
            ->get();

        // Catégories actuellement supervisées par l'utilisateur
        $categoriesSupervisees = $user->categoriesSupervisees()
            ->pluck('id_categorie')
            ->toArray();

        // Statistiques
        $stats = [
            'total_categories' => CategorieRessource::count(),
            'categories_avec_responsable' => CategorieRessource::whereNotNull('user_id')->count(),
            'categories_sans_responsable' => CategorieRessource::whereNull('user_id')->count(),
            'ressources_total' => Ressource::count(),
            'utilisateurs_eligibles' => User::where('statut', 'actif')
                ->where('role', '!=', 'admin')
                ->where('id', '!=', $user->id)
                ->count(),
        ];

        return view('utilisateurs.gestion-responsabilites', compact(
            'user',
            'categories',
            'categoriesSupervisees',
            'stats'
        ));
    }

    /**
     * Traiter la gestion des responsabilités
     */
    public function gestionResponsabilites(Request $request, User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('admin')) {
            abort(403, 'Accès non autorisé.');
        }

        // Validation des données
        $request->validate([
            'action' => 'required|in:changer_role,assigner_categories',
            'nouveau_role' => 'required_if:action,changer_role|in:admin,responsable,user',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categorie_ressources,id_categorie',
        ]);

        return DB::transaction(function () use ($request, $user) {
            
            if ($request->action === 'changer_role') {
                return $this->changerRoleUtilisateur($user, $request->nouveau_role);
                
            } elseif ($request->action === 'assigner_categories') {
                return $this->gererCategoriesResponsable($user, $request->categories ?? []);
            }

            return back()->with('error', 'Action non reconnue.');
        });
    }

    /**
     * Changer le rôle d'un utilisateur (méthode interne)
     */
    private function changerRoleUtilisateur(User $user, $nouveauRole)
    {
        $ancienRole = $user->role;

        // Si l'utilisateur était responsable et devient autre chose
        if ($ancienRole === 'responsable' && $nouveauRole !== 'responsable') {
            // Retirer toutes les catégories supervisées
            $nombreCategories = $user->categoriesSupervisees()->count();
            $user->removeAllCategories();
            
            $user->role = $nouveauRole;
            $user->save();

            return back()->with('success', 
                "Rôle changé de '{$ancienRole}' à '{$nouveauRole}'. " .
                "{$nombreCategories} catégorie(s) ont été retirées.");
        }

        // Si l'utilisateur devient responsable
        if ($nouveauRole === 'responsable') {
            $user->role = $nouveauRole;
            $user->save();

            return back()->with('success', 
                "L'utilisateur est maintenant responsable. " .
                "Vous pouvez maintenant lui assigner des catégories.");
        }

        // Changement de rôle simple
        $user->role = $nouveauRole;
        $user->save();

        return back()->with('success', "Rôle changé de '{$ancienRole}' à '{$nouveauRole}'.");
    }

    /**
     * Gérer les catégories d'un responsable (méthode interne)
     */
    private function gererCategoriesResponsable(User $user, array $categoriesSelectionnees)
    {
        // Vérifier que l'utilisateur est responsable
        if ($user->role !== 'responsable') {
            return back()->with('error', 
                "Cet utilisateur n'est pas responsable. Changez d'abord son rôle.");
        }

        // Récupérer les catégories actuellement supervisées
        $categoriesActuelles = $user->categoriesSupervisees()
            ->pluck('id_categorie')
            ->toArray();

        // Catégories à ajouter
        $categoriesAAjouter = array_diff($categoriesSelectionnees, $categoriesActuelles);
        
        // Catégories à retirer
        $categoriesARetirer = array_diff($categoriesActuelles, $categoriesSelectionnees);

        $resultats = [
            'ajoutees' => 0,
            'retirees' => 0,
            'erreurs' => []
        ];

        // Retirer les catégories
        foreach ($categoriesARetirer as $categorieId) {
            $categorie = CategorieRessource::find($categorieId);
            if ($categorie) {
                $categorie->removeResponsable();
                $resultats['retirees']++;
            }
        }

        // Ajouter les nouvelles catégories
        foreach ($categoriesAAjouter as $categorieId) {
            $categorie = CategorieRessource::find($categorieId);
            
            if (!$categorie) {
                continue;
            }

            // Si la catégorie a déjà un responsable, le remplacer
            if ($categorie->hasResponsable() && $categorie->user_id !== $user->id) {
                $ancienResponsable = $categorie->responsable;
                $categorie->removeResponsable();
                
                // Notifier l'ancien responsable si nécessaire
                // ...
            }

            // Assigner la catégorie au nouvel utilisateur
            $categorie->assignResponsable($user);
            $resultats['ajoutees']++;
        }

        // Préparer le message de succès
        $message = "Modifications enregistrées avec succès. ";
        
        if ($resultats['ajoutees'] > 0) {
            $message .= "{$resultats['ajoutees']} catégorie(s) ajoutée(s). ";
        }
        
        if ($resultats['retirees'] > 0) {
            $message .= "{$resultats['retirees']} catégorie(s) retirée(s). ";
        }

        return back()->with('success', $message);
    }

    /**
     * API: Assigner/retirer une catégorie (AJAX)
     */
    public function toggleAssignationCategorie(Request $request, User $user, $categorieId)
    {
        // Vérifier les autorisations
        if (!Gate::allows('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        $categorie = CategorieRessource::findOrFail($categorieId);

        return DB::transaction(function () use ($user, $categorie) {
            
            $action = '';
            $message = '';

            if ($categorie->user_id === $user->id) {
                // Retirer la catégorie
                $categorie->removeResponsable();
                $action = 'retiree';
                $message = "Catégorie '{$categorie->nom}' retirée de {$user->full_name}.";
            } else {
                // Vérifier que l'utilisateur peut être responsable
                if (!$user->isEligibleForResponsability()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cet utilisateur n'est pas éligible pour être responsable."
                    ], 400);
                }

                // Si la catégorie a déjà un responsable, le remplacer
                if ($categorie->hasResponsable()) {
                    $ancienResponsable = $categorie->responsable;
                    $categorie->removeResponsable();
                }

                // Assigner la catégorie
                $categorie->assignResponsable($user);
                $action = 'assignee';
                $message = "Catégorie '{$categorie->nom}' assignée à {$user->full_name}.";
            }

            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'categorie' => [
                    'id' => $categorie->id_categorie,
                    'nom' => $categorie->nom,
                    'responsable' => $categorie->responsable ? [
                        'id' => $categorie->responsable->id,
                        'nom' => $categorie->responsable->full_name
                    ] : null
                ],
                'utilisateur' => [
                    'id' => $user->id,
                    'nom' => $user->full_name,
                    'role' => $user->role,
                    'categories_count' => $user->categoriesSupervisees()->count()
                ]
            ]);
        });
    }

    /**
     * Retirer toutes les catégories d'un responsable
     */
    public function retirerToutesCategories(Request $request, User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('admin')) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier que l'utilisateur est responsable
        if ($user->role !== 'responsable') {
            return back()->with('error', "Cet utilisateur n'est pas responsable.");
        }

        return DB::transaction(function () use ($user) {
            $nombreCategories = $user->categoriesSupervisees()->count();
            
            // Retirer toutes les catégories
            $user->removeAllCategories();
            
            // Changer le rôle si nécessaire
            if ($nombreCategories > 0) {
                $user->role = 'user';
                $user->save();
            }

            return back()->with('success', 
                "Toutes les catégories ({$nombreCategories}) ont été retirées. " .
                "Le rôle a été changé à 'user'.");
        });
    }

    /**
     * Changer seulement le rôle sans affecter les catégories
     */
    public function changerRoleSeul(Request $request, User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('admin')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'nouveau_role' => 'required|in:admin,responsable,user',
        ]);

        $ancienRole = $user->role;
        $nouveauRole = $request->nouveau_role;

        // Logique spéciale pour le changement de rôle
        if ($ancienRole === 'responsable' && $nouveauRole !== 'responsable') {
            // Demander confirmation avant de retirer les catégories
            if (!$request->has('confirmation')) {
                return back()->with('warning', 
                    "Changer le rôle de 'responsable' à '{$nouveauRole}' retirera toutes les catégories supervisées. " .
                    "Veuillez confirmer cette action.");
            }
        }

        return $this->changerRoleUtilisateur($user, $nouveauRole);
    }

    /**
     * Afficher le formulaire de transfert de responsabilité
     */
    public function showTransfertResponsabilite(User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('admin')) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier que l'utilisateur est responsable
        if (!$user->hasSupervisedCategories()) {
            return redirect()->route('users.gestion-responsabilites.form', $user)
                ->with('error', 'Cet utilisateur n\'est responsable d\'aucune catégorie.');
        }

        // Catégories supervisées par l'utilisateur
        $categoriesSupervisees = $user->categoriesSupervisees()
            ->withCount('ressources')
            ->get();

        // Utilisateurs éligibles pour recevoir les responsabilités
        $utilisateursEligibles = User::where('id', '!=', $user->id)
            ->where('statut', 'actif')
            ->where('role', '!=', 'admin')
            ->orderBy('nom')
            ->get();

        return view('utilisateurs.transfert-responsabilite', compact(
            'user',
            'categoriesSupervisees',
            'utilisateursEligibles'
        ));
    }

    /**
     * Traiter le transfert de responsabilité
     */
    public function transfertResponsabilite(Request $request, User $user)
    {
        // Vérifier les autorisations
        if (!Gate::allows('admin')) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'nouvel_utilisateur_id' => 'required|exists:users,id',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categorie_ressources,id_categorie',
        ]);

        $nouvelUtilisateur = User::findOrFail($request->nouvel_utilisateur_id);

        return DB::transaction(function () use ($user, $nouvelUtilisateur, $request) {
            
            $categoriesTransferes = [];
            $erreurs = [];

            foreach ($request->categories as $categorieId) {
                $categorie = CategorieRessource::find($categorieId);
                
                if (!$categorie || $categorie->user_id !== $user->id) {
                    $erreurs[] = "La catégorie ID {$categorieId} n'est pas supervisée par cet utilisateur.";
                    continue;
                }

                // Vérifier que le nouvel utilisateur est éligible
                if (!$nouvelUtilisateur->isEligibleForResponsability()) {
                    $erreurs[] = "Le nouvel utilisateur n'est pas éligible pour être responsable.";
                    break;
                }

                // Transférer la catégorie
                $categorie->assignResponsable($nouvelUtilisateur);
                $categoriesTransferes[] = $categorie->nom;
            }

            // Préparer le message de résultat
            if (count($categoriesTransferes) > 0) {
                $message = count($categoriesTransferes) . " catégorie(s) transférée(s) avec succès: " .
                          implode(', ', $categoriesTransferes);
                
                return back()->with('success', $message);
            }

            return back()->with('error', 
                "Aucun transfert effectué. " . 
                (count($erreurs) > 0 ? implode(' ', $erreurs) : ''));
        });
    }
}