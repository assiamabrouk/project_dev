<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'maintenances';
    protected $primaryKey = 'id_maintenance';
    public $timestamps = true;

    protected $fillable = [
        'date_debut',
        'date_fin',
        'motif',
        'id_ressource'
    ];

    // علاقات
    public function ressource()
    {
        return $this->belongsTo(Ressource::class, 'id_ressource');
    }
}
