<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CategorieRessource;

class CategorieRessourcePolicy
{
    /**
     * Vérifie si l'utilisateur peut voir la liste des catégories
     * Admin: Oui
     * Responsable: Oui (voir seulement ses catégories)
     * User: Oui (lecture seule)
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'responsable', 'user']);
    }

    /**
     * Vérifie si l'utilisateur peut voir une catégorie spécifique
     * Admin: Oui (toutes)
     * Responsable: Oui (seulement ses catégories)
     * User: Oui (toutes en lecture seule)
     */
    public function view(User $user, CategorieRessource $categorie)
    {
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'responsable') {
            return $categorie->id_utilisateur === $user->id;
        }
        
        return $user->role === 'user'; // Lecture seule pour les users normaux
    }

    /**
     * Vérifie si l'utilisateur peut créer une catégorie
     * Admin: Oui
     * Responsable: Non
     * User: Non
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur peut modifier une catégorie
     * Admin: Oui (toutes)
     * Responsable: Oui (seulement ses catégories)
     * User: Non
     */
    public function update(User $user, CategorieRessource $categorie)
    {
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'responsable') {
            return $categorie->id_utilisateur === $user->id;
        }
        
        return false;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer une catégorie
     * Admin: Oui (seulement si pas de ressources)
     * Responsable: Non
     * User: Non
     */
    public function delete(User $user, CategorieRessource $categorie)
    {
        if ($user->role === 'admin') {
            // Vérifier qu'il n'y a pas de ressources associées
            return $categorie->ressources()->count() === 0;
        }
        
        return false;
    }

    /**
     * Vérifie si l'utilisateur peut restaurer une catégorie
     * Admin seulement
     */
    public function restore(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur peut forcer la suppression
     * Admin seulement
     */
    public function forceDelete(User $user)
    {
        return $user->role === 'admin';
    }
}