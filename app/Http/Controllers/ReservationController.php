<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Ressource;
use App\Models\DecisionReservation;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $reservations = Reservation::with(['ressource', 'utilisateur'])->orderBy('date_debut', 'desc')->get();
        } elseif ($user->role === 'responsable') {
            // Get categories managed by responsable
            $categories = $user->categorieRessources->pluck('id_categorie');

            // Get reservations for resources in those categories
            $reservations = Reservation::whereHas('ressource', function ($query) use ($categories) {
                $query->whereIn('id_categorie', $categories);
            })->with(['ressource', 'utilisateur'])->orderBy('date_debut', 'desc')->get();
        } else {
            $reservations = $user->reservations()->with('ressource')->orderBy('date_debut', 'desc')->get();
        }

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        $ressourceId = $request->query('ressource'); 

        $selectedRessource = null;
        if ($ressourceId) {
            $selectedRessource = Ressource::find($ressourceId);
        }

        $ressources = Ressource::where('statut', 'disponible')->get();

        return view('reservations.create', compact('ressources', 'selectedRessource'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_ressource' => 'required|exists:ressources,id_ressource',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'justification' => 'required|string|max:500',
        ]);

        // Check availability
        $exists = Reservation::where('id_ressource', $request->id_ressource)
            ->where(function ($query) use ($request) {
                $query->whereBetween('date_debut', [$request->date_debut, $request->date_fin])
                    ->orWhereBetween('date_fin', [$request->date_debut, $request->date_fin])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('date_debut', '<', $request->date_debut)
                            ->where('date_fin', '>', $request->date_fin);
                    });
            })
            ->whereIn('statut', ['approuvee', 'active'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['date_debut' => 'Cette ressource n\'est pas disponible pour la période choisie.'])->withInput();
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'id_ressource' => $request->id_ressource,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'justification' => $request->justification,
            'statut' => 'en_attente',
        ]);


        return redirect()->route('reservations.index')->with('success', 'Votre demande de réservation a été envoyée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::with(['ressource', 'utilisateur', 'decision'])->findOrFail($id);

        // Authorization check
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $reservation->user_id) {
            // Check if responsible
            $isResponsable = false;
            if ($user->role === 'responsable') {
                $categories = $user->categorieRessources->pluck('id_categorie')->toArray();
                if (in_array($reservation->ressource->id_categorie, $categories)) {
                    $isResponsable = true;
                }
            }

            if (!$isResponsable) {
                abort(403);
            }
        }

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Only if pending
        $reservation = Reservation::findOrFail($id);
        if ($reservation->statut !== 'en_attente' || $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $ressources = Ressource::where('statut', 'disponible')->get();
        return view('reservations.edit', compact('reservation', 'ressources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->statut !== 'en_attente' || $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'id_ressource' => 'required|exists:ressources,id_ressource',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'justification' => 'required|string|max:500',
        ]);

        // Check availability
        $exists = Reservation::where('id_ressource', $request->id_ressource)
            ->where('id_reservation', '!=', $id) // Exclude current reservation
            ->where(function ($query) use ($request) {
                $query->whereBetween('date_debut', [$request->date_debut, $request->date_fin])
                    ->orWhereBetween('date_fin', [$request->date_debut, $request->date_fin])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('date_debut', '<', $request->date_debut)
                            ->where('date_fin', '>', $request->date_fin);
                    });
            })
            ->whereIn('statut', ['approuvee', 'active'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['date_debut' => 'Cette ressource n\'est pas disponible pour la période choisie.'])->withInput();
        }

        $reservation->update([
            'id_ressource' => $request->id_ressource,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'justification' => $request->justification,
            'date_modification' => now(),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée.');
    }

    public function approve(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $this->authorizeDecision($reservation);

        $request->validate([
            'commentaire' => 'nullable|string|max:500',
        ]);

        DecisionReservation::create([
            'decision' => 'approuvee',
            'commentaire' => $request->commentaire,
            'date_decision' => now(),
            'id_reservation' => $reservation->id_reservation,
            'user_id' => Auth::id(),
        ]);

        $reservation->update(['statut' => 'approuvee']);

        // Create Notification
        Notification::create([
            'message' => "Votre réservation pour " . $reservation->ressource->nom . " a été approuvée.",
            'lu' => false,
            'user_id' => $reservation->user_id,
        ]);

        return redirect()->route('reservations.show', $id)->with('success', 'Réservation approuvée.');
    }

    public function reject(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $this->authorizeDecision($reservation);

        $request->validate([
            'commentaire' => 'required|string|max:500',
        ]);

        DecisionReservation::create([
            'decision' => 'refusee',
            'commentaire' => $request->commentaire,
            'date_decision' => now(),
            'id_reservation' => $reservation->id_reservation,
            'user_id' => Auth::id(),
        ]);

        $reservation->update(['statut' => 'refusee']);

        // Create Notification
        Notification::create([
            'message' => "Votre réservation pour " . $reservation->ressource->nom . " a été refusée. Raison : " . $request->commentaire,
            'lu' => false,
            'user_id' => $reservation->user_id,
        ]);

        return redirect()->route('reservations.show', $id)->with('success', 'Réservation refusée.');
    }

    private function authorizeDecision(Reservation $reservation)
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie')->toArray();
            if (in_array($reservation->ressource->id_categorie, $categories)) {
                return true;
            }
        }

        abort(403, 'Vous n\'êtes pas autorisé à traiter cette demande.');
    }
}
