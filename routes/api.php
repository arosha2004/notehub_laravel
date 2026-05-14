<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\DashboardController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

     Route::post('/logout', [AuthController::class, 'logout']);

     Route::get('/dashboard', [DashboardController::class, 'index']);
     Route::apiResource('notes', NoteController::class);

});