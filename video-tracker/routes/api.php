<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\LibraryController;

// Autenticación — obtener token
Route::post('/tokens/create', [AuthController::class, 'createToken']);

// Catálogo público
Route::get('/games',      [GameController::class, 'index']);
Route::get('/games/{game}', [GameController::class, 'show']);

// Rutas protegidas con token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn($request) => $request->user());
    Route::delete('/tokens/revoke', [AuthController::class, 'revokeToken']);

    Route::get('/library',           [LibraryController::class, 'index']);
    Route::post('/library',          [LibraryController::class, 'store']);
    Route::delete('/library/{videogame}', [LibraryController::class, 'destroy']);
});
