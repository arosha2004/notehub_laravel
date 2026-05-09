<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\NoteDashboard;
use App\Http\Controllers\NoteController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', NoteDashboard::class)->name('dashboard');
    Route::resource('notes', NoteController::class)->except(['index']);
});
