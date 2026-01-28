<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DecisionReservation extends Model
{
    protected $table = 'decision_reservations';
    public $timestamps = true;

    protected $fillable = [
        'decision',
        'commentaire',
        'date_decision',
        'id_reservation',
        'user_id',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

