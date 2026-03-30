<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeditationSession extends Model
{
    protected $fillable = [
        'user_id', 
        'session_date', 
        'duration_minutes', 
        'notes'
    ];

    // one session always belongs to one user, so we define inverse relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}