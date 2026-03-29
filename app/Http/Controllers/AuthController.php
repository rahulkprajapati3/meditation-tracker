<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- Show Forms ---
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function showLoginForm() {
        return view('auth.login');
    }

    // --- Core Logic ---
    public function register(Request $request) 
    {
        // validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        // user Creation
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // auto-login after registration
        Auth::login($user);

        // Redirect with Success Flash Message
        return redirect()->route('dashboard')->with('success', 'Account created successfully! Welcome aboard.');
    }

    public function login(Request $request) 
    {
        // validate input
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // prevent session fixation attacks
            $request->session()->regenerate();

            // redirect to 'intended' page (or dashboard as fallback)
            return redirect()->intended('dashboard')->with('success', 'Logged in successfully!');
        }

        // If attempt fails, send back with error and old email input
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) 
    {
        Auth::logout();

        // Standard secure logout process
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // CSRF reset

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}