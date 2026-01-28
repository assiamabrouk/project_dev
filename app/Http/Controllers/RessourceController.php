<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use App\Models\CategorieRessource;
use App\Models\Reservation;
use App\Models\Maintenance;
use App\Models\HistoriqueRessource;
use App\Models\Discussion;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RessourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Lister toutes les ressources avec filtrage
     */
    public function index(Request $request)
    {
        $query = Ressource::with('categorie');

        // Recherche (nom + description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrage par catégorie
        if ($request->filled('categorie')) {
            $query->where('id_categorie', $request->categorie);
        }

        // Tri
        $query->orderBy('created_at', 'desc');

        // 📄 Pagination
        $ressources = $query->paginate(12)->appends($request->query());

        // Données pour la vue
        $categories = CategorieRessource::all();
        $statuts = ['disponible', 'occupé', 'maintenance', 'inactif', 'réservé'];

        return view('ressources.index', compact('ressources', 'categories', 'statuts'));
    }


    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = CategorieRessource::all();
        return view('ressources.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle ressource
     */
    public function store(Request $request)
    {
        // Vérifier les autorisations
        $user = Auth::user();
        if ($user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie')->toArray();
            if (!in_array($request->id_categorie, $categories)) {
                abort(403, 'Vous ne pouvez créer des ressources que dans vos catégories.');
            }
        } elseif ($user->role === 'user') {
            abort(403, 'Vous n\'êtes pas autorisé à créer des ressources.');
        }

        // Validation
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:ressources,nom',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string|max:1000',
            'cpu' => 'required|string|max:50',
            'ram' => 'required|string|max:50',
            'capacite_stockage' => 'required|string|max:50',
            'bande_passante' => 'required|string|max:50',
            'os' => 'required|string|max:100',
            'localisation' => 'required|string|max:255',
            'statut' => 'required|string|in:disponible,occupé,maintenance,inactif,réservé',
            'id_categorie' => 'required|exists:categorie_ressources,id_categorie',
        ]);

        // Gestion de l'image
        if ($request->hasFile('img')) {
            // Générer un nom unique pour l'image
            $fileName = time() . '_' . uniqid() . '.' . $request->img->extension();
            $path = $request->file('img')->storeAs('ressources', $fileName, 'public');
            $validated['img'] = $path;
        }

        // Création de la ressource
        try {
            $ressource = Ressource::create($validated);

            // Si admin ou responsable, attribuer automatiquement le user_id de la catégorie
            if (Auth::check()) {
                $categorie = CategorieRessource::find($validated['id_categorie']);
                // Vous pouvez ajouter des logs ou notifications ici si nécessaire
            }

            return redirect()->route('ressources.show', $ressource->id_ressource)
                ->with('success', 'Ressource ajoutée avec succès !');
        } catch (\Exception $e) {
            // En cas d'erreur, supprimer l'image si elle a été uploadée
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la ressource: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher une ressource spécifique avec ses discussions
     */
    public function show($id)
    {
        $ressource = Ressource::with([
            'categorie',
            'categorie.user',
            'reservations' => function ($query) {
                $query->orderBy('date_debut', 'desc');
            },
            'reservations.utilisateur',
            'maintenances' => function ($query) {
                $query->orderBy('date_debut', 'desc');
            },
            // Charger les discussions non modérées avec leurs utilisateurs
            'discussions' => function ($query) {
                $query->where('is_moderated', false)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(5); // Limiter à 5 derniers commentaires
            }
        ])->findOrFail($id);

        // Vérifier si l'utilisateur peut voir la ressource
        $user = Auth::user();
        if ($user && $user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie')->toArray();
            if (!in_array($ressource->id_categorie, $categories) && $user->role !== 'admin') {
                abort(403, 'Vous n\'avez pas accès à cette ressource.');
            }
        }

        // Statistiques simples
        $stats = [
            'total_reservations' => $ressource->reservations()->count(),
            'reservations_actives' => $ressource->reservations()->whereIn('statut', ['approuvee', 'active'])->count(),
            'maintenances_count' => $ressource->maintenances()->count(),
            'discussions_count' => $ressource->discussions()->where('is_moderated', false)->count(),
        ];

        // Vérifier la disponibilité pour réservation
        $isAvailable = $this->checkAvailability($ressource);

        // Historique récent
        $historique = HistoriqueRessource::where('id_ressource', $id)
            ->with(['user', 'reservation'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('ressources.show', compact('ressource', 'stats', 'isAvailable', 'historique'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $ressource = Ressource::findOrFail($id);

        // Vérifier les autorisations
        $user = Auth::user();
        if ($user->role !== 'admin') {
            if ($user->role === 'responsable') {
                $categories = $user->categorieRessources->pluck('id_categorie')->toArray();
                if (!in_array($ressource->id_categorie, $categories)) {
                    abort(403, 'Vous n\'êtes pas autorisé à modifier cette ressource.');
                }
            } else {
                abort(403, 'Vous n\'êtes pas autorisé à modifier des ressources.');
            }
        }

        $categories = CategorieRessource::all();
        return view('ressources.edit', compact('ressource', 'categories'));
    }

    /**
     * Mettre à jour une ressource
     */
    public function update(Request $request, $id)
    {
        $ressource = Ressource::findOrFail($id);

        // Vérifier les autorisations
        $user = Auth::user();
        if ($user->role !== 'admin') {
            if ($user->role === 'responsable') {
                $categories = $user->categorieRessources->pluck('id_categorie')->toArray();
                if (!in_array($ressource->id_categorie, $categories)) {
                    abort(403, 'Vous n\'êtes pas autorisé à modifier cette ressource.');
                }
            } else {
                abort(403, 'Vous n\'êtes pas autorisé à modifier des ressources.');
            }
        }

        // Validation
        $validated = $request->validate([
            'nom' => "required|string|max:255|unique:ressources,nom,{$ressource->id_ressource},id_ressource",
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string|max:1000',
            'cpu' => 'required|string|max:50',
            'ram' => 'required|string|max:50',
            'capacite_stockage' => 'required|string|max:50',
            'bande_passante' => 'required|string|max:50',
            'os' => 'required|string|max:100',
            'localisation' => 'required|string|max:255',
            'statut' => 'required|string|in:disponible,occupé,maintenance,inactif,réservé',
            'id_categorie' => 'required|exists:categorie_ressources,id_categorie',
        ]);

        try {
            // Sauvegarder l'ancienne image
            $oldImage = $ressource->img;

            // Gestion de la nouvelle image
            if ($request->hasFile('img')) {
                $fileName = time() . '_' . uniqid() . '.' . $request->img->extension();
                $path = $request->file('img')->storeAs('ressources', $fileName, 'public');
                $validated['img'] = $path;

                // Supprimer l'ancienne image si elle existe
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            } else {
                // Garder l'ancienne image
                $validated['img'] = $oldImage;
            }

            // Si le statut change vers "maintenance", annuler les réservations futures
            if ($ressource->statut !== 'maintenance' && $request->statut === 'maintenance') {
                $this->cancelFutureReservations($ressource);
            }

            // Mettre à jour la ressource
            $ressource->update($validated);

            return redirect()->route('ressources.show', $ressource->id_ressource)
                ->with('success', 'Ressource modifiée avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer une ressource
     */
    public function destroy($id)
    {
        $ressource = Ressource::withCount(['reservations', 'maintenances', 'discussions'])->findOrFail($id);

        // Vérifier les autorisations
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Seul l\'administrateur peut supprimer des ressources.');
        }

        // Vérifier s'il y a des dépendances
        if ($ressource->reservations_count > 0) {
            return redirect()->route('ressources.show', $id)
                ->with('error', 'Impossible de supprimer: cette ressource a ' . $ressource->reservations_count . ' réservation(s) associée(s).');
        }

        if ($ressource->maintenances_count > 0) {
            return redirect()->route('ressources.show', $id)
                ->with('error', 'Impossible de supprimer: cette ressource a ' . $ressource->maintenances_count . ' maintenance(s) planifiée(s).');
        }

        // Note: On ne bloque pas la suppression si des discussions existent
        // Elles seront supprimées automatiquement grâce à cascadeOnDelete

        try {
            // Supprimer l'image associée
            if ($ressource->img && Storage::disk('public')->exists($ressource->img)) {
                Storage::disk('public')->delete($ressource->img);
            }

            // Supprimer la ressource (les discussions seront supprimées automatiquement)
            $ressource->delete();

            return redirect()->route('ressources.index')
                ->with('success', 'Ressource supprimée avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('ressources.show', $id)
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'une ressource (fonctionnalité simplifiée)
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:disponible,occupé,maintenance,inactif,réservé',
        ]);

        $ressource = Ressource::findOrFail($id);
        $oldStatus = $ressource->statut;
        $ressource->statut = $request->statut;
        $ressource->save();

        return redirect()->back()
            ->with('success', "Statut changé de '{$oldStatus}' à '{$request->statut}'");
    }

    /**
     * Vérifier si une ressource est disponible
     */
    private function checkAvailability($ressource)
    {
        if ($ressource->statut !== 'disponible') {
            return false;
        }

        // Vérifier s'il y a des réservations actives
        $hasActiveReservation = $ressource->reservations()
            ->whereIn('statut', ['approuvee', 'active'])
            ->where('date_fin', '>=', now())
            ->where('date_debut', '<=', now())
            ->exists();

        return !$hasActiveReservation;
    }

    /**
     * Annuler les réservations futures lors de la mise en maintenance
     */
    private function cancelFutureReservations($ressource)
    {
        $reservations = $ressource->reservations()
            ->where('statut', 'en_attente')
            ->where('date_debut', '>', now())
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->statut = 'refusee';
            $reservation->save();

            Notification::create([
                'message' => "Votre réservation pour " . $ressource->nom . " a été annulée en raison d'une mise en maintenance de la ressource.",
                'lu' => false,
                'user_id' => $reservation->user_id,
            ]);
        }
    }

    /**
     * Rechercher des ressources (pour API/ajax)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([]);
        }

        $ressources = Ressource::where('nom', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id_ressource', 'nom', 'statut']);

        return response()->json($ressources);
    }

    /**
     * Obtenir les statistiques d'une ressource
     */
    public function stats($id)
    {
        $ressource = Ressource::findOrFail($id);

        $stats = [
            'total_reservations' => $ressource->reservations()->count(),
            'reservations_actives' => $ressource->reservations()->whereIn('statut', ['approuvee', 'active'])->count(),
            'reservations_terminees' => $ressource->reservations()->where('statut', 'terminee')->count(),
            'maintenances_count' => $ressource->maintenances()->count(),
        ];

        return view('ressources.stats', compact('ressource', 'stats'));
    }
}
