<?php
// app/Http\Controllers/MaintenanceController.php
namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Ressource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $maintenances = Maintenance::with('ressource')->orderBy('date_debut', 'desc')->get();
        } elseif ($user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie');
            $maintenances = Maintenance::whereHas('ressource', function($query) use ($categories) {
                $query->whereIn('id_categorie', $categories);
            })->with('ressource')->orderBy('date_debut', 'desc')->get();
        } else {
            $maintenances = Maintenance::with('ressource')
                ->whereHas('ressource', function($query) {
                    $query->where('statut', 'maintenance');
                })
                ->orderBy('date_debut', 'desc')
                ->get();
        }

        return view('maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $ressources = Ressource::all();
        } else {
            $categories = $user->categorieRessources->pluck('id_categorie');
            $ressources = Ressource::whereIn('id_categorie', $categories)->get();
        }

        return view('maintenances.create', compact('ressources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ressource' => 'required|exists:ressources,id_ressource',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string|max:500',
        ]);

        // Mettre la ressource en maintenance
        $ressource = Ressource::find($validated['id_ressource']);
        $ressource->statut = 'maintenance';
        $ressource->save();

        // Annuler les réservations pendant la maintenance
        $reservations = $ressource->reservations()
            ->where('statut', 'en_attente')
            ->where(function($query) use ($validated) {
                $query->whereBetween('date_debut', [$validated['date_debut'], $validated['date_fin']])
                      ->orWhereBetween('date_fin', [$validated['date_debut'], $validated['date_fin']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('date_debut', '<', $validated['date_debut'])
                            ->where('date_fin', '>', $validated['date_fin']);
                      });
            })
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->update(['statut' => 'refusee']);
            // Notification aux utilisateurs
            Notification::create([
                'message' => "Votre réservation pour " . $ressource->nom . " a été annulée en raison d'une maintenance programmée du " . 
                           $validated['date_debut'] . " au " . $validated['date_fin'] . ".",
                'lu' => false,
                'user_id' => $reservation->user_id,
            ]);
        }

        Maintenance::create($validated);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance planifiée avec succès.');
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $ressource = $maintenance->ressource;
        
        // Remettre la ressource en disponibilité
        $ressource->statut = 'disponible';
        $ressource->save();
        
        $maintenance->delete();

        return redirect()->route('maintenances.index')->with('success', 'Maintenance supprimée et ressource remise en service.');
    }
}