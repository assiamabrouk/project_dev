<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    // Nom de la table associée au modèle
    protected $table = 'reservations';

    // Clé primaire personnalisée
    protected $primaryKey = 'id_reservation';

    // Activer la gestion automatique des timestamps (created_at et updated_at)
    public $timestamps = true;

    // Champs pouvant être remplis en masse (mass assignable)
    protected $fillable = [
        'date_debut',
        'date_fin',
        'justification',
        'statut',
        'user_id',
        'id_ressource'
    ];

    // Relation avec le modèle User (un utilisateur peut avoir plusieurs réservations)
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation avec le modèle Ressource (une réservation concerne une ressource)
    public function ressource()
    {
        return $this->belongsTo(Ressource::class, 'id_ressource');
    }

    // Relation avec l'historique des ressources (une réservation peut avoir un historique)
    public function historique()
    {
        return $this->hasOne(HistoriqueRessource::class, 'id_reservation');
    }

    // Relation avec la décision de réservation (une réservation peut avoir une décision)
    public function decision()
    {
        return $this->hasOne(DecisionReservation::class, 'id_reservation');
    }
}
