<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueRessource extends Model
{
    use HasFactory;

    protected $table = 'historique_ressources';
    protected $primaryKey = 'id_historique';
    public $timestamps = true;

    protected $fillable = [
        'id_ressource',
        'id_reservation',
        'user_id',
        'date_debut_utilisation',
        'date_fin_utilisation',
        'etat'
    ];

    // علاقات
    public function ressource()
    {
        return $this->belongsTo(Ressource::class, 'id_ressource');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
