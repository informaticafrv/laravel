<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Videogame;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    public function index()
    {
        $mejoresJuegos = Game::withAvg('videogames', 'puntuacion_personal')
            ->withCount('videogames')
            ->having('videogames_count', '>=', 1)
            ->orderByDesc('videogames_avg_puntuacion_personal')
            ->take(10)
            ->get();

        $juegosPopulares = Game::withCount('videogames')
            ->orderByDesc('videogames_count')
            ->take(10)
            ->get();

        $usuariosActivos = User::withCount([
                'games',
                'games as completados_count' => fn($q) => $q->where('videogames.estado', 'Completado'),
            ])
            ->orderByDesc('completados_count')
            ->take(10)
            ->get();

        $miPosicion = null;
        if (Auth::check()) {
            $completados = Videogame::where('user_id', Auth::id())
                ->where('estado', 'Completado')
                ->count();
            $miPosicion = User::withCount([
                    'games as completados_count' => fn($q) => $q->where('videogames.estado', 'Completado'),
                ])
                ->having('completados_count', '>', $completados)
                ->count() + 1;
        }

        return view('rankings', compact('mejoresJuegos', 'juegosPopulares', 'usuariosActivos', 'miPosicion'));
    }
}
