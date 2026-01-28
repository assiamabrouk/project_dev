<?php

namespace App\Http\Controllers;

use App\Models\CategorieRessource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Ressource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategorieRessourceController extends Controller
{
    /**
     * Afficher la liste des catégories avec pagination et recherche
     */
    public function index(Request $request)
    {
        // Construire la requête avec les relations nécessaires
        $query = CategorieRessource::with(['user', 'ressources']);

        // Recherche par nom de catégorie
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nom', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Trier par différentes options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pour les responsables: obtenir leurs catégories séparément
        $myCategories = null;
        if (Auth::check() && Auth::user()->role === 'responsable') {
            $myCategories = CategorieRessource::with(['user', 'ressources'])
                ->where('user_id', Auth::id())
                ->when($request->has('search') && $request->search != '', function ($q) use ($request) {
                    $q->where('nom', 'LIKE', "%{$request->search}%")
                        ->orWhere('description', 'LIKE', "%{$request->search}%");
                })
                ->orderBy($sortBy, $sortOrder)
                ->get();
        }

        // Obtenir toutes les catégories (pour pagination ou affichage complet)
        $categories = $query->get();

        // Statistiques pour le tableau de bord
        $stats = [
            'total_categories' => CategorieRessource::count(),
            'categories_actives' => CategorieRessource::has('ressources')->count(),
            'categories_sans_ressources' => CategorieRessource::doesntHave('ressources')->count(),
        ];

        // Section principale: Toutes les catégories 
        $displayCategories = $categories ?? collect();
        if (Auth::check() && Auth::user()->role === 'responsable' && isset($myCategories)) {
            $displayCategories = $displayCategories->whereNotIn('id_categorie', $myCategories->pluck('id_categorie'));
        }

        return view('categorie_ressources.index', compact('categories', 'stats', 'myCategories', 'displayCategories'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle catégorie
     */
    public function create()
    {
        // Vérifier l'autorisation
        if (!in_array(Auth::user()->role, ['admin', 'responsable'])) {
            return redirect()->route('categorie_ressources.index')
                ->with('error', 'Vous n\'êtes pas autorisé à créer des catégories.');
        }

        // Récupérer tous les utilisateurs pour le select du superviseur
        $users = User::all();

        return view('categorie_ressources.create', compact('users'));
    }


    /**
     * Stocker une nouvelle catégorie dans la base de données
     */
    public function store(Request $request)
    {
        // Vérifier les autorisations
        if (!in_array(Auth::user()->role, ['admin', 'responsable'])) {
            return redirect()->route('categorie_ressources.index')
                ->with('error', 'Vous n\'êtes pas autorisé à créer des catégories.');
        }

        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:categorie_ressources,nom',
            'description' => 'nullable|string|max:1000',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Traiter l'image
        $imgPath = null;
        if ($request->hasFile('img')) {
            // Créer un nom de fichier unique
            $fileName = time() . '_' . Auth::id() . '_' . uniqid() . '.' . $request->img->extension();

            // Stocker l'image dans le dossier public
            $imgPath = $request->file('img')->storeAs(
                'categorie_images',
                $fileName,
                'public'
            );
        }

        // Créer la catégorie
        $categorie = CategorieRessource::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'img' => $imgPath,
            'user_id' => $request->user_id,
        ]);

        // Changer le rôle de l'utilisateur assigné comme responsable
        $user = User::find($request->user_id);
        if ($user && $user->role !== 'admin') { // ne pas toucher aux admins
            $user->role = 'responsable';
            $user->save();
        }

        return redirect()->route('categorie_ressources.index')
            ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Afficher les détails d'une catégorie spécifique
     */
    public function show(string $id)
    {
        // Charger la catégorie avec ses relations
        $ressources = Ressource::where('id_categorie', $id)->get();
        $categorie = CategorieRessource::findOrFail($id);
        $stats = [
            'total_ressources' => $ressources->count(),
            'ressources_disponibles' => $ressources->where('statut', 'disponible')->count(),
            'ressources_maintenance' => $ressources->where('statut', 'en_maintenance')->count(),
            'ressources_occupes' => $ressources->where('statut', 'occupé')->count(),
            'ressources_inactives' => $ressources->where('statut', 'inactif')->count(),
            'ressources_reservees' => $ressources->where('statut', 'réservée')->count(),
        ];



        return view('categorie_ressources.show', compact('ressources', 'categorie', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition d'une catégorie
     */
    public function edit(string $id)
    {
        $categorie = CategorieRessource::findOrFail($id);

        // Vérifier les autorisations
        if (Auth::user()->role !== 'admin' && $categorie->user_id !== Auth::id()) {
            return redirect()->route('categorie_ressources.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette catégorie.');
        }

        $users = User::all();
        return view('categorie_ressources.edit', compact('categorie', 'users'));
    }

    /**
     * Mettre à jour une catégorie dans la base de données
     */
    public function update(Request $request, string $id)
    {
        // Récupérer la catégorie ou échouer
        $categorie = CategorieRessource::findOrFail($id);

        // Vérifier les autorisations :
        // - Admin : toujours autorisé
        // - Responsable : uniquement s'il est responsable de cette catégorie
        if (Auth::user()->role !== 'admin' && $categorie->user_id !== Auth::id()) {
            return redirect()->route('categorie_ressources.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette catégorie.');
        }

        // Validation des données
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => "required|string|max:255|unique:categorie_ressources,nom,{$categorie->id_categorie},id_categorie",
            'description' => 'nullable|string|max:1000',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // user_id n'est requis que pour admin
            'user_id' => Auth::user()->role === 'admin' ? 'required|exists:users,id' : 'nullable',
        ]);

        // Si l'utilisateur n'est pas admin, utiliser son propre ID
        $userId = Auth::user()->role === 'admin' ? $request->user_id : Auth::id();

        // En cas d'erreur de validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Sauvegarder l'ancien responsable (avant modification)
        $oldResponsableId = $categorie->user_id;

        // Sauvegarder l'ancienne image (pour suppression après succès)
        $oldImage = $categorie->img;

        // Traitement de la nouvelle image (si fournie)
        if ($request->hasFile('img')) {
            // Générer un nom de fichier unique
            $fileName = time() . '_' . Auth::id() . '_' . uniqid() . '.' . $request->img->extension();

            // Stocker la nouvelle image
            $imgPath = $request->file('img')->storeAs(
                'categorie_images',
                $fileName,
                'public'
            );

            // Mettre à jour le chemin de l'image
            $categorie->img = $imgPath;
        }

        try {
            // Mise à jour des données de la catégorie
            $categorie->nom = $request->nom;
            $categorie->description = $request->description;
            $categorie->user_id = $userId;
            $categorie->save();

            /*Gestion automatique des rôles des utilisateurs*/

            // 1️⃣ Promouvoir le nouveau responsable en "responsable" si nécessaire
            $newResponsableId = $userId;
            $newUser = User::find($newResponsableId);

            if ($newUser && $newUser->role === 'user') {
                $newUser->update(['role' => 'responsable']);
            }

            // 2️⃣ Vérifier l'ancien responsable
            if ($oldResponsableId !== $newResponsableId) {
                $oldUser = User::find($oldResponsableId);

                if ($oldUser && $oldUser->role === 'responsable') {

                    // Vérifier s'il est encore responsable d'au moins une catégorie
                    $stillResponsable = CategorieRessource::where('user_id', $oldResponsableId)->exists();

                    // S'il n'est plus responsable d'aucune catégorie → retour au rôle "user"
                    if (!$stillResponsable) {
                        $oldUser->update(['role' => 'user']);
                    }
                }
            }

            // Supprimer l'ancienne image si une nouvelle a été uploadée
            if ($request->hasFile('img') && $oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            // Journaliser l'action dans le système d'historique
            activity()
                ->causedBy(Auth::user())
                ->performedOn($categorie)
                ->log('a modifié la catégorie : ' . $categorie->nom);

            return redirect()->route('categorie_ressources.index')
                ->with('success', 'Catégorie mise à jour avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de la catégorie : ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Supprimer une catégorie de la base de données
     */
    public function destroy(string $id)
    {
        $categorie = CategorieRessource::withCount('ressources')->findOrFail($id);

        // Vérifier les autorisations
        if (Auth::user()->role !== 'admin' && $categorie->user_id !== Auth::id()) {
            return redirect()->route('categorie_ressources.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette catégorie.');
        }

        // Vérifier si la catégorie contient des ressources
        if ($categorie->ressources_count > 0) {
            return redirect()->route('categorie_ressources.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient ' . $categorie->ressources_count . ' ressources.');
        }

        try {
            // Supprimer l'image associée
            if ($categorie->img && Storage::disk('public')->exists($categorie->img)) {
                Storage::disk('public')->delete($categorie->img);
            }

            // Journaliser l'action avant la suppression
            activity()
                ->causedBy(Auth::user())
                ->performedOn($categorie)
                ->log('a supprimé la catégorie : ' . $categorie->nom);

            // Supprimer la catégorie
            $categorie->delete();

            return redirect()->route('categorie_ressources.index')
                ->with('success', 'Catégorie supprimée avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('categorie_ressources.index')
                ->with('error', 'Erreur lors de la suppression de la catégorie: ' . $e->getMessage());
        }
    }

    /**
     * Fonction pour obtenir les catégories au format JSON (pour les selects, etc.)
     */
    public function getCategoriesJson()
    {
        $categories = CategorieRessource::select('id_categorie as id', 'nom as text')
            ->orderBy('nom')
            ->get();

        return response()->json($categories);
    }

    /**
     * Afficher les statistiques des catégories
     */
    public function stats()
    {
        // Statistiques détaillées
        $stats = [
            'total_categories' => CategorieRessource::count(),
            'categories_par_utilisateur' => CategorieRessource::selectRaw('user_id, count(*) as count')
                ->with('user')
                ->groupBy('user_id')
                ->get(),
            'categories_sans_ressources' => CategorieRessource::doesntHave('ressources')->count(),
            'categories_avec_ressources' => CategorieRessource::has('ressources')->count(),
            'ressources_par_categorie' => CategorieRessource::withCount('ressources')
                ->orderBy('ressources_count', 'desc')
                ->get(),
        ];

        return view('categorie_ressources.stats', compact('stats'));
    }
}
