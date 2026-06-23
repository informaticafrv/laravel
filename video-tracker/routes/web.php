<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideogameController;
use App\Http\Controllers\CommentController;

// rutas públicas
Route::get('/', function () {
    return view('auth.login');
});

// Middleware
Route::middleware(['auth'])->group(function () {

    //dashboard
    Route::get('/dashboard', [VideogameController::class, 'dashboard'])->name('dashboard');

    //biblioteca y catálogo
    Route::get('/biblioteca', [VideogameController::class, 'index'])->name('videogames.index');
    Route::get('/catalogo', [VideogameController::class, 'catalogo'])->name('videogames.catalogo');
    Route::post('/votar/{game_id}', [VideogameController::class, 'votar'])->name('videogames.votar');

    //creación de juegos
    Route::get('/videojuegos/crear', [VideogameController::class, 'create'])->name('videogames.create');
    Route::post('/videojuegos', [VideogameController::class, 'store'])->name('videogames.store');
    Route::get('/api/search-igdb', [VideogameController::class, 'searchIgdb'])->name('api.search.igdb');

    //vista detalle y comentarios
    Route::get('/juegos/{id}', [VideogameController::class, 'show'])->name('games.show');
    Route::post('/games/{game}/comments', [CommentController::class, 'store'])->name('comments.store');
    //logros
    Route::post('/achievements/{id}/toggle', [VideogameController::class, 'toggleAchievement'])->name('achievements.toggle');

    //rankings
    Route::get('/rankings', [RankingController::class, 'index'])->name('rankings');

    //búsqueda global
    Route::get('/buscar', [SearchController::class, 'index'])->name('search');

    //perfil público y seguir usuarios
    Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/usuarios/{user}/seguir', [UserController::class, 'follow'])->name('users.follow');
    Route::delete('/usuarios/{user}/seguir', [UserController::class, 'unfollow'])->name('users.unfollow');

    //notificaciones
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');

    //exportar biblioteca a PDF
    Route::get('/biblioteca/exportar', [VideogameController::class, 'exportPdf'])->name('videogames.export');

    //rutas de perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //middlwware, solo se puede editar un juego si te pertenece
    Route::middleware(['game.owner'])->group(function () {
        //edición
        Route::get('/videojuegos/{id}/editar', [VideogameController::class, 'edit'])->name('videogames.edit');
        Route::put('/videojuegos/{id}', [VideogameController::class, 'update'])->name('videogames.update');
        //eliminación
        Route::delete('/videojuegos/{id}', [VideogameController::class, 'destroy'])->name('videogames.destroy');
    });

});
require __DIR__.'/auth.php';