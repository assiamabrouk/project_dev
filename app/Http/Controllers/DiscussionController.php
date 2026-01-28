<?php
// app/Http\Controllers/DiscussionController.php
namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index($ressourceId)
    {
        $ressource = Ressource::findOrFail($ressourceId);
        $discussions = Discussion::where('id_ressource', $ressourceId)
            ->where('is_moderated', false)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('discussions.index', compact('discussions', 'ressource'));
    }

    public function store(Request $request, $ressourceId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Discussion::create([
            'id_ressource' => $ressourceId,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_moderated' => false,
        ]);

        return back()->with('success', 'Message envoyé avec succès.');
    }

    public function moderate($id)
    {
        $user = Auth::user();
        $discussion = Discussion::findOrFail($id);
        
        // Vérifier que l'utilisateur est responsable de cette ressource
        $isResponsable = false;
        if ($user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie');
            $ressource = $discussion->ressource;
            if (in_array($ressource->id_categorie, $categories->toArray())) {
                $isResponsable = true;
            }
        }

        if ($user->role !== 'admin' && !$isResponsable) {
            abort(403);
        }

        $discussion->update(['is_moderated' => true]);

        return back()->with('success', 'Message modéré.');
    }

    public function destroy($id)
    {
        $discussion = Discussion::findOrFail($id);
        
        // Seuls l'auteur, les responsables et l'admin peuvent supprimer
        $user = Auth::user();
        $isResponsable = false;
        
        if ($user->role === 'responsable') {
            $categories = $user->categorieRessources->pluck('id_categorie');
            $ressource = $discussion->ressource;
            if (in_array($ressource->id_categorie, $categories->toArray())) {
                $isResponsable = true;
            }
        }

        if ($user->id !== $discussion->user_id && $user->role !== 'admin' && !$isResponsable) {
            abort(403);
        }

        $discussion->delete();

        return back()->with('success', 'Message supprimé.');
    }
}