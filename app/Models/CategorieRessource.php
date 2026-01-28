<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieRessource extends Model
{
    protected $table = 'categorie_ressources';
    protected $primaryKey = 'id_categorie';
    public $timestamps = true;

    protected $fillable = [
        'img',
        'nom',
        'description',
        'user_id'
    ];

    // علاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class, 'id_categorie');
    }
}
