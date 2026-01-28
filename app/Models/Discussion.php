<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $table = 'discussions';
    protected $primaryKey = 'id_discussion';
    public $timestamps = true;

    protected $fillable = [
        'id_ressource',
        'user_id',
        'message',
        'is_moderated'
    ];

    // علاقات
    public function ressource()
    {
        return $this->belongsTo(Ressource::class, 'id_ressource');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
