<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom'        => 'required|string|max:255',
            'prenom'     => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'telephone'  => 'nullable|string|max:20',
            'password'   => 'required|string|confirmed|min:6',
            'user_type'  => 'required|string|in:Ingénieur,Enseignant,Doctorant',
            'img'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gestion de l'image
        $imagePath = null;

        if ($request->hasFile('img')) {
            $imageName = time() . '_' . uniqid() . '.' .
                $request->file('img')->getClientOriginalExtension();

            // Stockage dans storage/app/public/user
            $imagePath = $request->file('img')->storeAs(
                'user',
                $imageName,
                'public'
            );
        }

        // Création de l'utilisateur
        $user = User::create([
            'nom'               => $validated['nom'],
            'prenom'            => $validated['prenom'],
            'email'             => $validated['email'],
            'telephone'         => $validated['telephone'] ?? null,
            'img'               => $imagePath, // ex: user/filename.png
            'password'          => Hash::make($validated['password']),
            'role'              => 'user',
            'user_type'         => $validated['user_type'],
            'statut'            => 'actif',
            'email_verified_at' => now(),
        ]);

        // Événement d'inscription
        event(new Registered($user));

        // Connexion automatique
        Auth::login($user);

        return redirect('/dashboard')
            ->with('success', 'Compte créé avec succès !');
    }
}
