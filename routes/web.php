<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeditationSessionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [MeditationSessionController::class, 'index'])->name('dashboard');
    
    // to save new session
    Route::post('/sessions', [MeditationSessionController::class, 'store'])->name('sessions.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});