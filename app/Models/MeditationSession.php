<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeditationSession extends Model
{
    // Security ke liye fillable variables define kiye hain
    protected $fillable = [
        'user_id', 
        'session_date', 
        'duration_minutes', 
        'notes'
    ];

    // Ek session hamesha ek User ko belong karta hai
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}