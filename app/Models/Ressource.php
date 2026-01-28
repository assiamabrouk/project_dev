<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $table = 'ressources';
    protected $primaryKey = 'id_ressource';
    public $timestamps = true;

    protected $fillable = [
        'nom',
        'img',
        'description',
        'cpu',
        'ram',
        'capacite_stockage',
        'bande_passante',
        'os',
        'localisation',
        'statut',
        'date_creation',
        'date_modification',
        'id_categorie'
    ];

    // علاقات
    public function categorie()
    {
        return $this->belongsTo(CategorieRessource::class, 'id_categorie');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_ressource');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'id_ressource');
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'id_ressource');
    }

    public function historique()
    {
        return $this->hasMany(HistoriqueRessource::class, 'id_ressource');
    }
}
