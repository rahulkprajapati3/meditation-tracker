<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeditationSession;
use Illuminate\Support\Facades\Auth;

class MeditationSessionController extends Controller
{
    // on dashboard to show stats and history
    public function index()
    {
        $user = Auth::user();
        
        // to show session in decending order
        $sessions = $user->meditationSessions()->orderBy('session_date', 'desc')->get();
        
        // To calculate total session
        $totalSessions = $sessions->count();
        $totalMinutes = $sessions->sum('duration_minutes');

        return view('dashboard', compact('sessions', 'totalSessions', 'totalMinutes'));
    }

    // to save new session in database
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'session_date' => 'required|date|before_or_equal:today',
            'duration_minutes' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // to save data through relationship
        $session = Auth::user()->meditationSessions()->create([
            'session_date' => $request->session_date,
            'duration_minutes' => $request->duration_minutes,
            'notes' => $request->notes,
        ]);
 
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Session logged successfully via Timer!',
                'session' => $session
            ]);
        }
        return redirect()->route('dashboard')->with('success', 'Meditation session logged successfully!');
    }

    // apihistory
    public function apiHistory()
    {
        $sessions = auth()->user()->meditationSessions()->orderBy('session_date', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $sessions
        ], 200);
    }
}