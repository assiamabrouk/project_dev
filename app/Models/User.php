<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'img',
        'password',
        'user_type',
        'role',
        'statut',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function categorieRessources()
    {
        return $this->hasMany(CategorieRessource::class, 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // Méthode helper pour le nom complet
    public function getFullNameAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // Scope pour les utilisateurs actifs
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    public function canManageRessource($ressource)
    {
        return $this->role === 'admin'
            || (
                $this->role === 'responsable'
                && $ressource->categorie
                && $ressource->categorie->user_id === $this->id
            );
    }

}