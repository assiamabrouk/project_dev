<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = true;

    protected $fillable = [
        'message',
        'lu',
        'user_id'
    ];

    // علاقات
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
