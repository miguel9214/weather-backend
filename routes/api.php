<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas (registro y login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Autenticación
    Route::get('/user', function () {
        return auth()->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Búsquedas del clima
    Route::get('/searches',           [SearchController::class, 'index']);      // Listar búsquedas
    Route::post('/searches',          [SearchController::class, 'store']);      // Crear nueva búsqueda
    Route::get('/searches/{id}',      [SearchController::class, 'show']);       // Ver una búsqueda
    Route::put('/searches/{id}',      [SearchController::class, 'update']);     // Actualizar ciudad
    Route::delete('/searches/{id}',   [SearchController::class, 'destroy']);    // Eliminar búsqueda

    // Favoritos
    Route::get('/favorites',                  [FavoriteController::class, 'index']);          // Obtener favoritos
    Route::post('/favorites/{id}/toggle',     [FavoriteController::class, 'toggleFavorite']); // Alternar favorito
});
